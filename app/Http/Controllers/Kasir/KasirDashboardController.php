<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $kasir = auth()->user();
        $hariIni = now()->toDateString();

        // Transaksi offline hari ini
        $transaksiHariIni = Pemesanan::whereDate('tanggal_pemesanan', $hariIni)
            ->where('jenis_pemesanan', 'Offline')
            ->count();

        // Total pendapatan hari ini (yang sudah lunas)
        $pendapatanHariIni = Pembayaran::whereDate('tanggal_pembayaran', $hariIni)
            ->where('status_pembayaran', 'Lunas')
            ->sum('nominal_dibayar');

        // Pemesanan offline yang belum dibayar
        $pemesananBelumBayar = Pemesanan::where('status_pemesanan', 'Menunggu Bayar')
            ->where('jenis_pemesanan', 'Offline')
            ->count();

        // Jadwal tayang hari ini
        $jadwalHariIni = Jadwal::whereDate('tanggal_tayang', $hariIni)
            ->where('status_jadwal', 'Active')
            ->count();

        return view('kasir.dashboard', compact(
            'kasir',
            'transaksiHariIni',
            'pendapatanHariIni',
            'pemesananBelumBayar',
            'jadwalHariIni'
        ));
    }
}