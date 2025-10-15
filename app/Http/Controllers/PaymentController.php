<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pemesanan;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman pilih metode pembayaran
     */
    public function show($pemesanan_id)
    {
        $pemesanan = Pemesanan::with([
            'jadwal.film',
            'jadwal.studio',
            'detailPemesanans.kursi',
            'pembayaran'
        ])->findOrFail($pemesanan_id);

        // Cek kepemilikan
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini.');
        }

        // Cek status
        if ($pemesanan->status_pemesanan === 'Lunas') {
            return redirect()->route('invoice.show', $pemesanan_id)
                           ->with('info', 'Pemesanan sudah lunas.');
        }

        if ($pemesanan->status_pemesanan === 'Kadaluarsa') {
            return redirect()->route('home')
                           ->with('error', 'Pemesanan sudah kadaluarsa.');
        }

        return view('films.payment', compact('pemesanan'));
    }

    /**
     * Proses pembayaran - simpan metode dan redirect ke waiting
     */
    public function process(Request $request, $pemesanan_id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:DANA,OVO,GoPay,ShopeePay,BCA,BRI,BNI,Mandiri'
        ]);

        $pemesanan = Pemesanan::findOrFail($pemesanan_id);

        // Cek kepemilikan
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        // Cek status
        if ($pemesanan->status_pemesanan !== 'Menunggu Bayar') {
            return redirect()->route('invoice.show', $pemesanan_id)
                           ->with('info', 'Pemesanan sudah diproses.');
        }

        // Cek atau buat pembayaran
        $pembayaran = $pemesanan->pembayaran;

        if (!$pembayaran) {
            // Buat pembayaran baru
            Pembayaran::create([
                'pemesanan_id' => $pemesanan->pemesanan_id,
                'user_id' => auth()->id(),
                'metode_bayar' => 'E-Wallet', // Default
                'metode_online' => $request->metode_pembayaran,
                'nominal_dibayar' => $pemesanan->total_bayar,
                'tanggal_pembayaran' => now(),
                'status_pembayaran' => 'Pending',
                'jenis_pembayaran' => 'Online',
                'status_verifikasi' => 'pending',
            ]);
        } else {
            // Update pembayaran yang ada
            $pembayaran->update([
                'metode_online' => $request->metode_pembayaran,
                'jenis_pembayaran' => 'Online',
                'status_verifikasi' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
                'catatan_verifikasi' => null,
            ]);
        }

        // Redirect ke waiting
        return redirect()->route('payment.waiting', $pemesanan_id);
    }

    /**
     * Halaman QR Code + Timer + Waiting
     */
    public function waiting($pemesanan_id)
    {
        $pemesanan = Pemesanan::with([
            'jadwal.film',
            'jadwal.studio',
            'detailPemesanans.kursi',
            'pembayaran'
        ])->findOrFail($pemesanan_id);

        // Cek kepemilikan
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        // Jika belum ada pembayaran
        if (!$pemesanan->pembayaran) {
            return redirect()->route('payment.show', $pemesanan_id)
                           ->with('error', 'Silakan pilih metode pembayaran terlebih dahulu.');
        }

        // Jika sudah approved
        if ($pemesanan->pembayaran->status_verifikasi === 'approved') {
            return redirect()->route('invoice.show', $pemesanan_id)
                           ->with('success', 'Pembayaran berhasil dikonfirmasi!');
        }

        // Jika rejected
        if ($pemesanan->pembayaran->status_verifikasi === 'rejected') {
            $catatan = $pemesanan->pembayaran->catatan_verifikasi ?? 'Pembayaran ditolak';
            return redirect()->route('payment.show', $pemesanan_id)
                           ->with('error', 'Pembayaran ditolak: ' . $catatan);
        }

        return view('films.payment-waiting', compact('pemesanan'));
    }

    /**
     * Check status (AJAX Polling)
     */
    public function checkStatus($pemesanan_id)
    {
        $pemesanan = Pemesanan::with('pembayaran')->findOrFail($pemesanan_id);

        // Cek kepemilikan
        if ($pemesanan->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pembayaran = $pemesanan->pembayaran;

        if (!$pembayaran) {
            return response()->json([
                'status' => 'pending',
                'message' => 'Menunggu konfirmasi...'
            ]);
        }

        if ($pembayaran->status_verifikasi === 'approved') {
            // Update status pemesanan
            $pemesanan->update(['status_pemesanan' => 'Lunas']);

            return response()->json([
                'status' => 'approved',
                'message' => 'Pembayaran berhasil!',
                'redirect' => route('invoice.show', $pemesanan_id)
            ]);
        }

        if ($pembayaran->status_verifikasi === 'rejected') {
            return response()->json([
                'status' => 'rejected',
                'message' => 'Pembayaran ditolak',
                'redirect' => route('payment.show', $pemesanan_id)
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'message' => 'Menunggu konfirmasi admin...'
        ]);
    }

    // BACKWARD COMPATIBILITY
    public function create($pemesanan_id)
    {
        return $this->show($pemesanan_id);
    }

    public function store(Request $request, $pemesanan_id)
    {
        return $this->process($request, $pemesanan_id);
    }
}