<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KasirPemesananController extends Controller
{
    // Tampilkan halaman pemesanan offline
    public function index()
    {
        // Ambil jadwal yang aktif dan tanggal tayang >= hari ini
        $jadwals = Jadwal::with('film', 'studio')
            ->where('status_jadwal', 'Active')
            ->where('tanggal_tayang', '>=', now()->toDateString())
            ->orderBy('tanggal_tayang')
            ->orderBy('jam_mulai')
            ->get();

        return view('kasir.pemesanan-offline', compact('jadwals'));
    }

    // API: Ambil kursi yang tersedia untuk jadwal tertentu
    public function getKursiAvailable($jadwal_id)
    {
        try {
            // Ambil jadwal beserta studio dan kursinya
            $jadwal = Jadwal::with('studio.kursis')->findOrFail($jadwal_id);

            // Cari kursi yang sudah terjual (status pemesanan bukan dibatalkan)
            $kursiTerjual = DetailPemesanan::join('pemesanans', 'detail_pemesanans.pemesanan_id', '=', 'pemesanans.pemesanan_id')
                ->where('pemesanans.jadwal_id', $jadwal_id)
                ->where('pemesanans.status_pemesanan', '!=', 'Dibatalkan')
                ->pluck('detail_pemesanans.kursi_id')
                ->toArray();

            // Map kursi dengan status tersedia atau tidak
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

    // Simpan pemesanan offline
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwals,jadwal_id',
            'kursi_ids' => 'required|string', // Akan menerima JSON string
        ]);

        // Decode JSON string kursi_ids
        $kursiIds = json_decode($validated['kursi_ids'], true);

        // Validasi kursi_ids
        if (empty($kursiIds) || !is_array($kursiIds)) {
            return back()->with('error', 'Pilih minimal 1 kursi!');
        }

        try {
            // Ambil jadwal
            $jadwal = Jadwal::findOrFail($validated['jadwal_id']);

            // Generate kode transaksi unik
            $kodeTransaksi = 'TRX-' . now()->format('YmdHis') . '-' . Str::random(4);

            // Hitung total harga
            $totalHarga = $jadwal->harga_reguler * count($kursiIds);

            // Buat pemesanan
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

            // Buat detail pemesanan (per kursi)
            foreach ($kursiIds as $kursi_id) {
                DetailPemesanan::create([
                    'pemesanan_id' => $pemesanan->pemesanan_id,
                    'kursi_id' => $kursi_id,
                    'harga_per_kursi' => $jadwal->harga_reguler,
                ]);
            }

            // Redirect ke pembayaran
            return redirect()->route('kasir.pembayaran.index', $pemesanan->pemesanan_id)
                ->with('success', 'Pemesanan berhasil dibuat! Lanjut ke pembayaran.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}