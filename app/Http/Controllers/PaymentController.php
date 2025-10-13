<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pemesanan;

class PaymentController extends Controller
{
      /**
     * Menampilkan form/instruksi pembayaran.
     */
    public function create($pemesanan_id)
    {
        $pemesanan = Pemesanan::with(['jadwal.film', 'jadwal.studio', 'detailPemesanans.kursi'])
                              ->findOrFail($pemesanan_id);
        
        // Cek status pemesanan
        if ($pemesanan->status_pemesanan !== 'Menunggu Bayar') {
            return redirect()->route('invoice.show', $pemesanan_id)
                             ->with('info', 'Pemesanan ini sudah dibayar.');
        }
        
        return view('films.payment', compact('pemesanan'));
    }

    /**
     * Memproses pembayaran dan mengupdate status.
     */
    public function store(Request $request, $pemesanan_id)
    {
        $request->validate([
            'metode_bayar' => 'required|in:Tunai,Debit/Kredit,E-Wallet,Transfer Bank',
            'nominal_dibayar' => 'required|numeric|min:0',
        ]);
        
        $pemesanan = Pemesanan::findOrFail($pemesanan_id);
        
        // Validasi nominal
        if ($request->nominal_dibayar < $pemesanan->total_bayar) {
            return back()->with('error', 'Nominal pembayaran kurang dari total yang harus dibayar.');
        }
        
        // 1. Simpan record pembayaran
        Pembayaran::create([
             'pemesanan_id' => $pemesanan->pemesanan_id,
             'metode_bayar' => $request->metode_bayar,
             'nominal_dibayar' => $request->nominal_dibayar,
             'tanggal_pembayaran' => now(),
             'status_pembayaran' => 'Lunas',
             'user_id' => auth()->id() ?? 1,
        ]);
        
        // 2. Update status pemesanan
        $pemesanan->update([
            'status_pemesanan' => 'Lunas',
        ]);
        
        return redirect()->route('invoice.show', $pemesanan->pemesanan_id)
                         ->with('success', 'Pembayaran berhasil! Terima kasih.');
    }

    public function show($pemesanan_id)
{
    $pemesanan = Pemesanan::with(['jadwal.film', 'jadwal.studio', 'detailPemesanans.kursi'])
                          ->findOrFail($pemesanan_id);

    return view('films.payment', compact('pemesanan'));
}

}
