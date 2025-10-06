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
        
        // Jika status belum lunas, mungkin diarahkan ke halaman pembayaran
        if ($pemesanan->status_pemesanan !== 'Lunas') {
             return redirect()->route('payment.show', $pemesanan_id)
                              ->with('warning', 'Pemesanan belum lunas. Silakan selesaikan pembayaran.');
        }
        
        return view('films.invoice', compact('pemesanan'));
    }
}
