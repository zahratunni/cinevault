<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $startDate = $request->input('start_date', Carbon::now()->subMonths(6)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Convert to Carbon instances
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Stats Cards (All Time)
        $totalFilms = Film::count();
        $totalJadwals = Jadwal::where('status_jadwal', 'Active')->count();
        $totalPelanggan = User::where('role', 'Customer')->count();
        $totalKasir = User::where('role', 'Kasir')->count();
        
        // Revenue Stats (Filtered by date range)
        $totalRevenue = Pemesanan::where('status_pemesanan', 'Lunas')
            ->whereBetween('tanggal_pemesanan', [$start, $end])
            ->sum('total_bayar');
            
        $todayRevenue = Pemesanan::where('status_pemesanan', 'Lunas')
            ->whereDate('tanggal_pemesanan', Carbon::today())
            ->sum('total_bayar');
        
        // Booking Stats (Filtered by date range)
        $totalBookings = Pemesanan::whereBetween('tanggal_pemesanan', [$start, $end])->count();
        $todayBookings = Pemesanan::whereDate('tanggal_pemesanan', Carbon::today())->count();
        $pendingBookings = Pemesanan::where('status_pemesanan', 'Menunggu Bayar')->count();
        
        // Film Stats by Status
        $playingNowCount = Film::where('status_tayang', 'Playing Now')->count();
        $upcomingCount = Film::where('status_tayang', 'Upcoming')->count();
        
        // Recent Bookings (Last 10, filtered)
        $recentBookings = Pemesanan::with(['user', 'jadwal.film'])
            ->whereBetween('tanggal_pemesanan', [$start, $end])
            ->orderBy('tanggal_pemesanan', 'desc')
            ->limit(10)
            ->get();
        
        // Top Films (Most Booked in date range)
        $topFilms = Film::select('films.*')
            ->join('jadwals', 'films.film_id', '=', 'jadwals.film_id')
            ->join('pemesanans', 'jadwals.jadwal_id', '=', 'pemesanans.jadwal_id')
            ->where('pemesanans.status_pemesanan', 'Lunas')
            ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
            ->select('films.*', DB::raw('COUNT(pemesanans.pemesanan_id) as total_bookings'))
            ->groupBy('films.film_id')
            ->orderBy('total_bookings', 'desc')
            ->limit(5)
            ->get();
        
        // Daily/Monthly Revenue Chart Data (depends on date range)
        $daysDiff = $start->diffInDays($end);
        
        if ($daysDiff <= 31) {
            // Show daily data (max 31 days)
            $revenueData = Pemesanan::select(
                    DB::raw('DATE(tanggal_pemesanan) as period'),
                    DB::raw('SUM(total_bayar) as total')
                )
                ->where('status_pemesanan', 'Lunas')
                ->whereBetween('tanggal_pemesanan', [$start, $end])
                ->groupBy('period')
                ->orderBy('period', 'asc')
                ->get();
            $chartLabel = 'Pendapatan Harian';
        } else {
            // Show monthly data
            $revenueData = Pemesanan::select(
                    DB::raw('DATE_FORMAT(tanggal_pemesanan, "%Y-%m") as period'),
                    DB::raw('SUM(total_bayar) as total')
                )
                ->where('status_pemesanan', 'Lunas')
                ->whereBetween('tanggal_pemesanan', [$start, $end])
                ->groupBy('period')
                ->orderBy('period', 'asc')
                ->get();
            $chartLabel = 'Pendapatan Bulanan';
        }
        
        // Booking Status Distribution (filtered)
        $bookingStatus = Pemesanan::select('status_pemesanan', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pemesanan', [$start, $end])
            ->groupBy('status_pemesanan')
            ->get();
        
        // Calculate percentage changes (compare with previous period)
        $previousStart = $start->copy()->sub($start->diffInDays($end), 'days');
        $previousEnd = $start->copy()->subDay();
        
        $previousRevenue = Pemesanan::where('status_pemesanan', 'Lunas')
            ->whereBetween('tanggal_pemesanan', [$previousStart, $previousEnd])
            ->sum('total_bayar');
        
        $revenueChange = $previousRevenue > 0 
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 
            : 0;

        return view('admin.dashboard', compact(
            'totalFilms',
            'totalJadwals',
            'totalPelanggan',
            'totalKasir',
            'totalRevenue',
            'todayRevenue',
            'totalBookings',
            'todayBookings',
            'pendingBookings',
            'playingNowCount',
            'upcomingCount',
            'recentBookings',
            'topFilms',
            'revenueData',
            'chartLabel',
            'bookingStatus',
            'startDate',
            'endDate',
            'revenueChange'
        ));
    }
}