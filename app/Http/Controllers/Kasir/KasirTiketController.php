<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class KasirTiketController extends Controller
{
    // Halaman untuk search kode booking (khusus customer online)
    public function search()
    {
        return view('kasir.search-tiket');
    }

    // Tampilkan tiket untuk dicetak
    public function show($pemesanan_id)
    {
        try {
            // Ambil pemesanan dengan relasi
            $pemesanan = Pemesanan::with('jadwal.film', 'jadwal.studio', 'detailPemesanans.kursi', 'pembayaran')
                ->findOrFail($pemesanan_id);

            // Cek apakah pemesanan sudah lunas
            if ($pemesanan->status_pemesanan !== 'Lunas') {
                return redirect()->route('kasir.dashboard')
                    ->with('error', 'Tiket hanya bisa dicetak untuk pemesanan yang sudah lunas.');
            }

            return view('kasir.cetak-tiket', compact('pemesanan'));
        } catch (\Exception $e) {
            return redirect()->route('kasir.dashboard')
                ->with('error', 'Pemesanan tidak ditemukan.');
        }
    }

    // Cari pemesanan berdasarkan kode booking
    public function cari(Request $request)
    {
        $validated = $request->validate([
            'kode_booking' => 'required|string',
        ]);

        $pemesanan = Pemesanan::where('kode_transaksi', $validated['kode_booking'])->first();

        if (!$pemesanan) {
            return back()->with('error', 'Kode booking tidak ditemukan!');
        }

        if ($pemesanan->status_pemesanan !== 'Lunas') {
            return back()->with('error', 'Pemesanan belum lunas! Tidak bisa cetak tiket.');
        }

        // Redirect ke halaman cetak tiket
        return redirect()->route('kasir.tiket.show', $pemesanan->pemesanan_id);
    }
}