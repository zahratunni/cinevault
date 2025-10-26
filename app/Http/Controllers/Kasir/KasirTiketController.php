<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class KasirTiketController extends Controller
{
    /**
     * Halaman search kode booking (untuk customer online)
     */
    public function search()
    {
        return view('kasir.search-tiket');
    }

    /**
     * Cari pemesanan berdasarkan kode booking
     */
    public function cari(Request $request)
    {
        $validated = $request->validate([
            'kode_booking' => 'required|string',
        ]);

        try {
            $pemesanan = Pemesanan::where('kode_transaksi', $validated['kode_booking'])->first();

            // ❌ VALIDASI 1: Kode tidak ditemukan
            if (!$pemesanan) {
                return back()->with('error', 'Kode booking tidak ditemukan!');
            }

            // ❌ VALIDASI 2: Belum lunas
            if ($pemesanan->status_pemesanan !== 'Lunas') {
                return back()->with('error', 'Pemesanan belum lunas! Status: ' . $pemesanan->status_pemesanan);
            }

            // 🚨 VALIDASI 3: Tiket sudah dicetak (CRITICAL!)
            if ($pemesanan->tiket_dicetak_at) {
                // Log fraud attempt
                \Log::warning('🚨 FRAUD ATTEMPT: Tiket sudah dicetak!', [
                    'kode_booking' => $validated['kode_booking'],
                    'dicetak_pada' => $pemesanan->tiket_dicetak_at,
                    'kasir_attempt' => auth()->user()->name,
                    'kasir_id' => auth()->id(),
                ]);

                return back()->with('error', '🚨 ALERT! Tiket ini SUDAH PERNAH DICETAK pada ' . 
                    \Carbon\Carbon::parse($pemesanan->tiket_dicetak_at)->format('d/m/Y H:i') . 
                    ' WIB. JANGAN CETAK ULANG!');
            }

            // ✅ Log aktivitas kasir (hanya jika lolos validasi)
            \Log::info('Kasir - Cari Tiket', [
                'kode_booking' => $validated['kode_booking'],
                'kasir_id' => auth()->id(),
                'kasir_name' => auth()->user()->name,
            ]);

            return redirect()->route('kasir.tiket.show', $pemesanan->pemesanan_id);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan tiket untuk dicetak
     */
    public function show($pemesanan_id)
    {
        try {
            $pemesanan = Pemesanan::with([
                'jadwal.film', 
                'jadwal.studio', 
                'detailPemesanans.kursi', 
                'pembayaran'
            ])->findOrFail($pemesanan_id);

            // Validasi status
            if ($pemesanan->status_pemesanan !== 'Lunas') {
                return redirect()->route('kasir.dashboard')
                    ->with('error', 'Tiket hanya bisa dicetak untuk pemesanan yang sudah lunas.');
            }

            // 🚨 VALIDASI TAMBAHAN: Double check tiket belum dicetak
            if ($pemesanan->tiket_dicetak_at) {
                return redirect()->route('kasir.tiket.search')
                    ->with('error', '🚨 Tiket sudah dicetak sebelumnya!');
            }

            // ✅ UPDATE: Tandai tiket sudah dicetak
            $pemesanan->update([
                'tiket_dicetak_at' => now(),
            ]);

            // Log aktivitas cetak
            \Log::info('Kasir - Cetak Tiket', [
                'pemesanan_id' => $pemesanan_id,
                'kode_booking' => $pemesanan->kode_transaksi,
                'jumlah_tiket' => $pemesanan->detailPemesanans->count(),
                'kasir_id' => auth()->id(),
                'kasir_name' => auth()->user()->name,
                'tiket_dicetak_at' => now()->toDateTimeString(),
            ]);

            return view('kasir.cetak-tiket', compact('pemesanan'));
        } catch (\Exception $e) {
            return redirect()->route('kasir.dashboard')
                ->with('error', 'Pemesanan tidak ditemukan.');
        }
    }
}