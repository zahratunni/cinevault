<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KasirProfileController extends Controller
{
    /**
     * Display the kasir's profile (PURE READ ONLY)
     * 
     * Kasir TIDAK dapat mengubah apapun
     * Semua perubahan data (profile & password) hanya bisa dilakukan oleh Admin
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get transaction statistics
        $totalTransactions = $user->pembayarans()
            ->where('status_pembayaran', 'Lunas')
            ->count();
            
        $totalRevenue = $user->pembayarans()
            ->where('status_pembayaran', 'Lunas')
            ->join('pemesanans', 'pembayarans.pemesanan_id', '=', 'pemesanans.pemesanan_id')
            ->sum('pemesanans.total_bayar');
        
        return view('kasir.profile.index', compact('user', 'totalTransactions', 'totalRevenue'));
    }
}