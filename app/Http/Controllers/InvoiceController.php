<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;

class InvoiceController extends Controller
{
    /**
     * Menampilkan invoice/e-tiket setelah pembayaran lunas.
     */
    public function show($pemesanan_id)
    {
        $pemesanan = Pemesanan::with(['jadwal.film', 'jadwal.studio', 'detailPemesanans.kursi', 'pembayaran'])
                              ->findOrFail($pemesanan_id);
        
        // Cek kepemilikan (hanya untuk customer, kasir/admin bisa akses semua)
        if (auth()->user()->role === 'Customer' && $pemesanan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini.');
        }
        
        return view('films.invoice', compact('pemesanan'));
    }
}