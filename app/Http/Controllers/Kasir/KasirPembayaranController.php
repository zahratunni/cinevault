<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class KasirPembayaranController extends Controller
{
    // Tampilkan halaman pembayaran offline
    public function index($pemesanan_id)
    {
        try {
            // Ambil pemesanan beserta relasi
            $pemesanan = Pemesanan::with('jadwal.film', 'jadwal.studio', 'detailPemesanans.kursi', 'pembayaran')
                ->findOrFail($pemesanan_id);

            // Jika sudah lunas, redirect ke invoice
            if ($pemesanan->status_pemesanan === 'Lunas') {
                return redirect()->route('kasir.invoice.show', $pemesanan_id)
                    ->with('info', 'Pemesanan sudah dibayar. Lihat invoice di bawah.');
            }

            return view('kasir.pembayaran-offline', compact('pemesanan'));
        } catch (\Exception $e) {
            return redirect()->route('kasir.dashboard')
                ->with('error', 'Pemesanan tidak ditemukan.');
        }
    }

    // Proses pembayaran offline
    public function store(Request $request, $pemesanan_id)
    {
        // Validasi input
        $validated = $request->validate([
            'metode_bayar' => 'required|in:Tunai,Debit/Kredit,E-Wallet,Transfer Bank',
            'nominal_dibayar' => 'required|numeric|min:0',
        ]);

        try {
            // Ambil pemesanan
            $pemesanan = Pemesanan::findOrFail($pemesanan_id);

            // Cek nominal pembayaran
            if ($validated['nominal_dibayar'] < $pemesanan->total_bayar) {
                return back()->with('error', 'Nominal pembayaran kurang! Minimal Rp ' . number_format($pemesanan->total_bayar, 0, ',', '.'));
            }

            // Buat atau update pembayaran
            Pembayaran::updateOrCreate(
                ['pemesanan_id' => $pemesanan_id],
                [
                    'user_id' => auth()->id(),
                    'metode_bayar' => $validated['metode_bayar'],
                    'nominal_dibayar' => $validated['nominal_dibayar'],
                    'tanggal_pembayaran' => now(),
                    'status_pembayaran' => 'Lunas',
                    'jenis_pembayaran' => 'Offline',
                ]
            );

            // Update status pemesanan menjadi Lunas
            $pemesanan->update(['status_pemesanan' => 'Lunas']);

            // Redirect ke invoice
            return redirect()->route('kasir.tiket.show', $pemesanan_id)
    ->with('success', 'Pembayaran berhasil! Silahkan print tiket.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}