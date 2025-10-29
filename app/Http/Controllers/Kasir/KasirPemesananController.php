<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KasirPemesananController extends Controller
{
    // ==========================================
    // BAGIAN 1: PEMESANAN BARU (CREATE)
    // ==========================================
    
    /**
     * Tampilkan halaman pemesanan offline
     */
    public function index()
    {
        $jadwals = Jadwal::with('film', 'studio')
            ->where('status_jadwal', 'Active')
            ->where('tanggal_tayang', '>=', now()->toDateString())
            ->orderBy('tanggal_tayang')
            ->orderBy('jam_mulai')
            ->get();

        return view('kasir.pemesanan-offline', compact('jadwals'));
    }

    /**
     * API: Ambil kursi yang tersedia untuk jadwal tertentu
     */
    public function getKursiAvailable($jadwal_id)
    {
        try {
            $jadwal = Jadwal::with('studio.kursis')->findOrFail($jadwal_id);

            // Cari kursi yang sudah terjual (kecuali yang dibatalkan)
            $kursiTerjual = DetailPemesanan::join('pemesanans', 'detail_pemesanans.pemesanan_id', '=', 'pemesanans.pemesanan_id')
                ->where('pemesanans.jadwal_id', $jadwal_id)
                ->where('pemesanans.status_pemesanan', '!=', 'Dibatalkan')
                ->pluck('detail_pemesanans.kursi_id')
                ->toArray();

            $kursis = $jadwal->studio->kursis->map(function ($kursi) use ($kursiTerjual) {
                return [
                    'kursi_id' => $kursi->kursi_id,
                    'kode_kursi' => $kursi->kode_kursi,
                    'baris' => $kursi->baris,
                    'nomor_kursi' => $kursi->nomor_kursi,
                    'tersedia' => !in_array($kursi->kursi_id, $kursiTerjual)
                ];
            });

            return response()->json([
                'success' => true,
                'kursis' => $kursis,
                'harga' => $jadwal->harga_reguler
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simpan pemesanan offline baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwals,jadwal_id',
            'kursi_ids' => 'required|string',
        ]);

        $kursiIds = json_decode($validated['kursi_ids'], true);

        if (empty($kursiIds) || !is_array($kursiIds)) {
            return back()->with('error', 'Pilih minimal 1 kursi!');
        }

        try {
            $jadwal = Jadwal::findOrFail($validated['jadwal_id']);
            $kodeTransaksi = 'TRX-' . now()->format('YmdHis') . '-' . Str::random(4);
            $totalHarga = $jadwal->harga_reguler * count($kursiIds);

            $pemesanan = Pemesanan::create([
                'user_id' => auth()->id(),
                'jadwal_id' => $validated['jadwal_id'],
                'kode_transaksi' => $kodeTransaksi,
                'jenis_pemesanan' => 'Offline',
                'status_pemesanan' => 'Menunggu Bayar',
                'harga_dasar_total' => $totalHarga,
                'total_bayar' => $totalHarga,
                'tanggal_pemesanan' => now(),
            ]);

            foreach ($kursiIds as $kursi_id) {
                DetailPemesanan::create([
                    'pemesanan_id' => $pemesanan->pemesanan_id,
                    'kursi_id' => $kursi_id,
                    'harga_per_kursi' => $jadwal->harga_reguler,
                ]);
            }

            return redirect()->route('kasir.pembayaran.index', $pemesanan->pemesanan_id)
                ->with('success', 'Pemesanan berhasil dibuat! Lanjut ke pembayaran.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ==========================================
    // BAGIAN 2: KELOLA PEMESANAN OFFLINE (READ/UPDATE/DELETE)
    // ==========================================

    /**
     * Halaman Kelola Pemesanan Offline
     * Menampilkan daftar semua pemesanan offline dengan filter
     */
   public function kelolaPemesanan(Request $request)
{
    $query = Pemesanan::with(['jadwal.film', 'jadwal.studio', 'pembayaran', 'user'])
        ->where('jenis_pemesanan', 'Offline')
        ->whereHas('jadwal') // ⭐ TAMBAHKAN INI - Filter hanya yang punya jadwal
        ->whereHas('jadwal.film') // ⭐ TAMBAHKAN INI - Filter hanya yang punya film
        ->latest();

    // Filter by status
    if ($request->has('status') && $request->status != '') {
        $query->where('status_pemesanan', $request->status);
    }

    // Filter by date
    if ($request->has('tanggal') && $request->tanggal != '') {
        $query->whereDate('created_at', $request->tanggal);
    }

    // Search by kode transaksi
    if ($request->has('search') && $request->search != '') {
        $query->where('kode_transaksi', 'like', '%' . $request->search . '%');
    }

    $pemesanans = $query->paginate(15);

    return view('kasir.kelola-pemesanan.index', compact('pemesanans'));
}

    /**
     * Detail Pemesanan Offline
     * Menampilkan detail lengkap pemesanan untuk diproses
     */
    public function detailPemesanan($pemesanan_id)
    {
        $pemesanan = Pemesanan::with([
            'jadwal.film', 
            'jadwal.studio', 
            'pembayaran', 
            'detailPemesanans.kursi',
            'user'
        ])->findOrFail($pemesanan_id);

        // Validasi: hanya pemesanan offline yang bisa diakses
        if ($pemesanan->jenis_pemesanan != 'Offline') {
            return redirect()->route('kasir.kelola.pemesanan')
                ->with('error', 'Hanya pemesanan offline yang dapat dikelola di halaman ini');
        }

        return view('kasir.kelola-pemesanan.detail', compact('pemesanan'));
    }

    /**
     * Konfirmasi Pembayaran Cash
     * Kasir konfirmasi pembayaran tunai dari customer
     */
    /**
     * Batalkan Pemesanan
     * Membatalkan pemesanan dan mengembalikan kursi ke pool
     */
    public function cancelPemesanan($pemesanan_id)
    {
        $pemesanan = Pemesanan::findOrFail($pemesanan_id);

        // Validasi: tidak bisa batalkan yang sudah lunas
        if ($pemesanan->status_pemesanan == 'Lunas') {
            return back()->with('error', 'Pemesanan yang sudah lunas tidak dapat dibatalkan!');
        }

        if ($pemesanan->status_pemesanan == 'Dibatalkan') {
            return back()->with('error', 'Pemesanan sudah dibatalkan sebelumnya!');
        }

        try {
            // Update status pemesanan
            $pemesanan->update(['status_pemesanan' => 'Dibatalkan']);

            // Update status pembayaran jika ada
            if ($pemesanan->pembayaran) {
                $pemesanan->pembayaran->update([
                    'status_pembayaran' => 'Gagal',
                    'status_verifikasi' => 'rejected',
                ]);
            }

            // Kursi otomatis tersedia lagi
            // Karena di getKursiAvailable() sudah filter status != 'Dibatalkan'

            return redirect()
                ->route('kasir.kelola.pemesanan')
                ->with('success', 'Pemesanan berhasil dibatalkan. Kursi telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}