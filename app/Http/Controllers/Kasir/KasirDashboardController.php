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

    // Transaksi offline hari ini HANYA DARI KASIR INI
    $transaksiHariIni = Pemesanan::whereDate('tanggal_pemesanan', $hariIni)
        ->where('jenis_pemesanan', 'Offline')
        ->where('user_id', auth()->id()) // Tambahkan ini
        ->count();

    // Pemesanan offline yang belum dibayar DARI KASIR INI
    $pemesananBelumBayar = Pemesanan::where('status_pemesanan', 'Menunggu Bayar')
        ->where('jenis_pemesanan', 'Offline')
        ->where('user_id', auth()->id()) // Tambahkan ini
        ->count();

    // Jadwal tayang hari ini (global, semua kasir lihat sama)
    $jadwalHariIni = Jadwal::whereDate('tanggal_tayang', $hariIni)
        ->where('status_jadwal', 'Active')
        ->count();

    return view('kasir.dashboard', compact(
        'kasir',
        'transaksiHariIni',
        'pemesananBelumBayar',
        'jadwalHariIni'
    ));
}
}