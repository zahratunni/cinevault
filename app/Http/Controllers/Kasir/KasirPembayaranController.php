<?php

namespace App\Http\Controllers\Kasir;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class KasirPembayaranController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Halaman konfirmasi pembayaran walk-in
     */
    public function index($pemesanan_id)
    {
        try {
            $pemesanan = Pemesanan::with([
                'jadwal.film', 
                'jadwal.studio', 
                'detailPemesanans.kursi', 
                'pembayaran',
                'user'
            ])->findOrFail($pemesanan_id);

            // Jika sudah lunas, redirect ke tiket
            if ($pemesanan->status_pemesanan === 'Lunas') {
                return redirect()->route('kasir.tiket.show', $pemesanan_id)
                    ->with('info', 'Pemesanan sudah lunas. Silakan cetak tiket.');
            }

            return view('kasir.pembayaran.index', compact('pemesanan'));
        } catch (\Exception $e) {
            return redirect()->route('kasir.dashboard')
                ->with('error', 'Pemesanan tidak ditemukan.');
        }
    }

    /**
     * Konfirmasi pembayaran TUNAI
     * Total otomatis dari booking, kasir hanya konfirmasi
     */
   /**
 * Konfirmasi pembayaran TUNAI
 */
public function confirmTunai(Request $request, $pemesanan_id)
{
    try {
        $pemesanan = Pemesanan::with('user')->findOrFail($pemesanan_id);
        
        // Validasi status pemesanan
        if ($pemesanan->status_pemesanan === 'Lunas') {
            return back()->with('error', 'Pemesanan sudah lunas!');
        }

        if ($pemesanan->status_pemesanan === 'Dibatalkan') {
            return back()->with('error', 'Pemesanan sudah dibatalkan!');
        }

        // Validasi nominal pembayaran
        $request->validate([
            'nominal_dibayar' => 'required|numeric|min:' . $pemesanan->total_bayar,
        ], [
            'nominal_dibayar.required' => 'Nominal pembayaran harus diisi',
            'nominal_dibayar.numeric' => 'Nominal harus berupa angka',
            'nominal_dibayar.min' => 'Nominal minimal Rp ' . number_format($pemesanan->total_bayar, 0, ',', '.'),
        ]);

        $kembalian = $request->nominal_dibayar - $pemesanan->total_bayar;

        // Cek apakah sudah ada data pembayaran
        $pembayaran = $pemesanan->pembayaran;

        if (!$pembayaran) {
            // Buat pembayaran baru
            Pembayaran::create([
                'pemesanan_id' => $pemesanan->pemesanan_id,
                'user_id' => $pemesanan->user_id ?? auth()->id(),
                'metode_bayar' => 'Tunai',
                'jenis_pembayaran' => 'Offline',
                'nominal_dibayar' => $request->nominal_dibayar,
                'tanggal_pembayaran' => now(),
                'status_pembayaran' => 'Lunas',
                'status_verifikasi' => 'approved',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);
        } else {
            // Update pembayaran yang sudah ada
            $pembayaran->update([
                'metode_bayar' => 'Tunai',
                'jenis_pembayaran' => 'Offline',
                'nominal_dibayar' => $request->nominal_dibayar,
                'status_pembayaran' => 'Lunas',
                'status_verifikasi' => 'approved',
                'tanggal_pembayaran' => now(),
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'user_id' => $pemesanan->user_id ?? auth()->id(),
            ]);
        }
        
        // Update status pemesanan menjadi Lunas
        $pemesanan->update(['status_pemesanan' => 'Lunas']);
        
        \Log::info('Kasir - Pembayaran Tunai', [
            'pemesanan_id' => $pemesanan_id,
            'kasir_id' => auth()->id(),
            'total' => $pemesanan->total_bayar,
            'nominal_dibayar' => $request->nominal_dibayar,
            'kembalian' => $kembalian,
        ]);
        
        // ✅ Set session untuk trigger auto print dengan info kembalian
        return redirect()->route('kasir.tiket.show', $pemesanan_id)
            ->with('success', 'Pembayaran tunai berhasil dikonfirmasi! Kembalian: Rp ' . number_format($kembalian, 0, ',', '.'))
            ->with('auto_print', true);
            
    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    /**
     * Generate Midtrans untuk pembayaran online walk-in
     */
    /**
 * Generate Midtrans untuk pembayaran online walk-in
 */
public function createMidtrans($pemesanan_id)
{
    try {
        $pemesanan = Pemesanan::with([
            'jadwal.film', 
            'jadwal.studio', 
            'detailPemesanans.kursi', 
            'user'
        ])->findOrFail($pemesanan_id);

        // Cek apakah sudah lunas
        if ($pemesanan->status_pemesanan === 'Lunas') {
            return redirect()->route('kasir.tiket.show', $pemesanan_id)
                ->with('info', 'Pemesanan sudah lunas.');
        }

        // Generate unique transaction ID
        $transaction_id = 'KASIR-' . $pemesanan->pemesanan_id . '-' . time();

        // Buat/update pembayaran MIDTRANS
        $pembayaran = Pembayaran::updateOrCreate(
            ['pemesanan_id' => $pemesanan->pemesanan_id],
            [
                'user_id' => $pemesanan->user_id ?? auth()->id(),
                'metode_bayar' => 'Online',  // ✅ Ubah ini sesuai ENUM database
                'jenis_pembayaran' => 'Offline',  // ✅ Ubah dari 'Offline-Kasir' ke 'Offline'
                'nominal_dibayar' => $pemesanan->total_bayar,
                'tanggal_pembayaran' => now(),
                'status_pembayaran' => 'Pending',
                'transaction_id' => $transaction_id,
                'verified_by' => auth()->id(),
            ]
        );

        // Siapkan data untuk Midtrans
$params = [
    'transaction_details' => [
        'order_id' => $transaction_id,
        'gross_amount' => (int) $pemesanan->total_bayar,
    ],
    'customer_details' => [
        'first_name' => $pemesanan->user->name ?? 'Walk-in Customer',
        'email' => $pemesanan->user->email ?? 'walkin@cinevault.com',
        'phone' => $pemesanan->user->phone ?? '08123456789',
    ],
    'item_details' => [
        [
            'id' => $pemesanan->jadwal->jadwal_id,
            'price' => (int) $pemesanan->jadwal->harga_reguler,
            'quantity' => $pemesanan->detailPemesanans->count(),
            'name' => $pemesanan->jadwal->film->judul . ' - ' . $pemesanan->jadwal->studio->nama_studio,
        ]
    ],
    // ✅ UBAH INI - Tambahkan semua metode pembayaran
    'enabled_payments' => [
        'gopay', 
        'shopeepay', 
        'other_qris',
        'bca_va',      // ✅ BCA Virtual Account
        'bni_va',      // ✅ BNI Virtual Account
        'bri_va',      // ✅ BRI Virtual Account
        'mandiri_va',  // ✅ Mandiri Virtual Account (Mandiri Bill)
        'permata_va',  // ✅ Permata Virtual Account
        'other_va',    // ✅ VA Bank lainnya
    ],
];

        // Generate Snap Token
        $snapToken = Snap::getSnapToken($params);
        $pembayaran->update(['snap_token' => $snapToken]);

        // Log aktivitas
        \Log::info('Kasir - Generate Midtrans', [
            'pemesanan_id' => $pemesanan_id,
            'kasir_id' => auth()->id(),
            'transaction_id' => $transaction_id,
        ]);

        return view('kasir.pembayaran.qris', compact('pemesanan', 'snapToken'));
        
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
    }
}
    /**
     * Check status pembayaran (untuk polling)
     */
    public function checkStatus($pemesanan_id)
    {
        $pemesanan = Pemesanan::with('pembayaran')->findOrFail($pemesanan_id);
        
        return response()->json([
            'status' => $pemesanan->pembayaran->status_pembayaran ?? 'Pending',
            'pemesanan_id' => $pemesanan_id,
        ]);
    }

    /**
     * DEPRECATED - Backward compatibility
     */
    public function store(Request $request, $pemesanan_id)
    {
        $validated = $request->validate([
            'metode_bayar' => 'required|in:Tunai,Debit/Kredit,E-Wallet,Transfer Bank',
            'nominal_dibayar' => 'required|numeric|min:0',
        ]);

        try {
            $pemesanan = Pemesanan::findOrFail($pemesanan_id);

            if ($validated['nominal_dibayar'] < $pemesanan->total_bayar) {
                return back()->with('error', 'Nominal pembayaran kurang!');
            }

            Pembayaran::updateOrCreate(
                ['pemesanan_id' => $pemesanan_id],
                [
                    'user_id' => $pemesanan->user_id ?? auth()->id(),
                    'metode_bayar' => $validated['metode_bayar'],
                    'nominal_dibayar' => $validated['nominal_dibayar'],
                    'tanggal_pembayaran' => now(),
                    'status_pembayaran' => 'Lunas',
                    'jenis_pembayaran' => 'Offline',
                    'verified_by' => auth()->id(),
                ]
            );

            $pemesanan->update(['status_pemesanan' => 'Lunas']);

            return redirect()->route('kasir.tiket.show', $pemesanan_id)
                ->with('success', 'Pembayaran berhasil!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}