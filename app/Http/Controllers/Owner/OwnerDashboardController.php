<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Film;
use App\Models\Jadwal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OwnerDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Default date range: Last 30 days
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));

        // Convert to Carbon instances
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get dashboard data
        $data = $this->getDashboardData($start, $end, $startDate, $endDate);

        return view('owner.dashboard', $data);
    }

    public function exportPDF(Request $request)
    {
        // Get date range
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));

        // Convert to Carbon instances
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get dashboard data
        $data = $this->getDashboardData($start, $end, $startDate, $endDate);
        
        // Add generated date
        $data['generatedDate'] = Carbon::now()->format('d M Y H:i');

        // Generate PDF
        $pdf = Pdf::loadView('owner.report-pdf', $data)
            ->setPaper('a4', 'portrait');

        // Download PDF
        return $pdf->download('laporan-owner-' . $startDate . '-to-' . $endDate . '.pdf');
    }

    private function getDashboardData($start, $end, $startDate, $endDate)
    {
        // --- TOTAL PEMASUKAN (HANYA LUNAS) ---
        $totalRevenue = Pemesanan::whereBetween('tanggal_pemesanan', [$start, $end])
            ->where('status_pemesanan', 'Lunas')
            ->sum('total_bayar');

        // --- TOTAL TIKET TERJUAL ---
        $totalTickets = Pemesanan::whereBetween('tanggal_pemesanan', [$start, $end])
            ->where('status_pemesanan', 'Lunas')
            ->withCount('detailPemesanans')
            ->get()
            ->sum('detail_pemesanans_count');

        // --- TOTAL TRANSAKSI ---
        $totalTransactions = Pemesanan::whereBetween('tanggal_pemesanan', [$start, $end])
            ->where('status_pemesanan', 'Lunas')
            ->count();

        // --- RATA-RATA PER TRANSAKSI ---
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // --- PERBANDINGAN PERIODE SEBELUMNYA ---
        $previousStart = Carbon::parse($startDate)->subDays($end->diffInDays($start) + 1);
        $previousEnd = Carbon::parse($startDate)->subDay();

        $previousRevenue = Pemesanan::whereBetween('tanggal_pemesanan', [$previousStart, $previousEnd])
            ->where('status_pemesanan', 'Lunas')
            ->sum('total_bayar');

        $revenueChange = $previousRevenue > 0 
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 
            : 0;

        // --- BREAKDOWN ONLINE VS OFFLINE ---
        $onlineRevenue = Pemesanan::whereBetween('tanggal_pemesanan', [$start, $end])
            ->where('status_pemesanan', 'Lunas')
            ->where('jenis_pemesanan', 'Online')
            ->sum('total_bayar');

        $offlineRevenue = Pemesanan::whereBetween('tanggal_pemesanan', [$start, $end])
            ->where('status_pemesanan', 'Lunas')
            ->where('jenis_pemesanan', 'Offline')
            ->sum('total_bayar');

        $onlineCount = Pemesanan::whereBetween('tanggal_pemesanan', [$start, $end])
            ->where('status_pemesanan', 'Lunas')
            ->where('jenis_pemesanan', 'Online')
            ->count();

        $offlineCount = Pemesanan::whereBetween('tanggal_pemesanan', [$start, $end])
            ->where('status_pemesanan', 'Lunas')
            ->where('jenis_pemesanan', 'Offline')
            ->count();

        // --- GRAFIK PEMASUKAN (Daily/Monthly based on date range) ---
        $daysDifference = $start->diffInDays($end);
        
        if ($daysDifference <= 31) {
            // Daily chart
            $revenueData = Pemesanan::selectRaw('DATE(tanggal_pemesanan) as period, SUM(total_bayar) as total')
                ->whereBetween('tanggal_pemesanan', [$start, $end])
                ->where('status_pemesanan', 'Lunas')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
            
            $chartLabel = 'Pendapatan Harian (Rp)';
        } else {
            // Monthly chart
            $revenueData = Pemesanan::selectRaw('DATE_FORMAT(tanggal_pemesanan, "%Y-%m") as period, SUM(total_bayar) as total')
                ->whereBetween('tanggal_pemesanan', [$start, $end])
                ->where('status_pemesanan', 'Lunas')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
            
            $chartLabel = 'Pendapatan Bulanan (Rp)';
        }

        // --- TOP 5 FILM TERLARIS ---
        $topFilms = Film::select('films.*')
            ->join('jadwals', 'films.film_id', '=', 'jadwals.film_id')
            ->join('pemesanans', 'jadwals.jadwal_id', '=', 'pemesanans.jadwal_id')
            ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
            ->where('pemesanans.status_pemesanan', 'Lunas')
            ->groupBy('films.film_id')
            ->orderByRaw('COUNT(pemesanans.pemesanan_id) DESC')
            ->limit(5)
            ->withCount(['jadwals as total_bookings' => function($query) use ($start, $end) {
                $query->join('pemesanans', 'jadwals.jadwal_id', '=', 'pemesanans.jadwal_id')
                    ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
                    ->where('pemesanans.status_pemesanan', 'Lunas');
            }])
            ->get();

        // --- TRANSAKSI TERBARU ---
        $recentTransactions = Pemesanan::with(['user', 'jadwal.film'])
            ->whereBetween('tanggal_pemesanan', [$start, $end])
            ->where('status_pemesanan', 'Lunas')
            ->orderBy('tanggal_pemesanan', 'desc')
            ->limit(10)
            ->get();

        // --- STATUS PEMESANAN BREAKDOWN ---
        $bookingStatus = Pemesanan::selectRaw('status_pemesanan, COUNT(*) as total')
            ->whereBetween('tanggal_pemesanan', [$start, $end])
            ->groupBy('status_pemesanan')
            ->get();

        // --- TRANSAKSI HARI INI ---
        $todayTransactions = Pemesanan::whereDate('tanggal_pemesanan', Carbon::today())
            ->where('status_pemesanan', 'Lunas')
            ->count();

        $todayRevenue = Pemesanan::whereDate('tanggal_pemesanan', Carbon::today())
            ->where('status_pemesanan', 'Lunas')
            ->sum('total_bayar');

        // --- PENDING TRANSACTIONS ---
        $pendingTransactions = Pemesanan::whereBetween('tanggal_pemesanan', [$start, $end])
            ->where('status_pemesanan', 'Menunggu Bayar')
            ->count();

        return compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalTickets',
            'totalTransactions',
            'averageTransaction',
            'revenueChange',
            'onlineRevenue',
            'offlineRevenue',
            'onlineCount',
            'offlineCount',
            'revenueData',
            'chartLabel',
            'topFilms',
            'recentTransactions',
            'bookingStatus',
            'todayTransactions',
            'todayRevenue',
            'pendingTransactions'
        );
    }
}