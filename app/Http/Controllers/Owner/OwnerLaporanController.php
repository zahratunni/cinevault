<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Film;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OwnerLaporanController extends Controller
{
    /**
     * Laporan Per Film
     */
    public function laporanFilm(Request $request)
    {
        // Default date range: Last 30 days
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));

        // Convert to Carbon instances
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get all films with their statistics
        $films = Film::select('films.*')
            ->leftJoin('jadwals', 'films.film_id', '=', 'jadwals.film_id')
            ->leftJoin('pemesanans', function($join) use ($start, $end) {
                $join->on('jadwals.jadwal_id', '=', 'pemesanans.jadwal_id')
                    ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
                    ->where('pemesanans.status_pemesanan', 'Lunas');
            })
            ->groupBy('films.film_id')
            ->orderByRaw('SUM(pemesanans.total_bayar) DESC')
            ->get()
            ->map(function($film) use ($start, $end) {
                // Calculate statistics
                $totalRevenue = Pemesanan::join('jadwals', 'pemesanans.jadwal_id', '=', 'jadwals.jadwal_id')
                    ->where('jadwals.film_id', $film->film_id)
                    ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
                    ->where('pemesanans.status_pemesanan', 'Lunas')
                    ->sum('pemesanans.total_bayar');

                $totalTransactions = Pemesanan::join('jadwals', 'pemesanans.jadwal_id', '=', 'jadwals.jadwal_id')
                    ->where('jadwals.film_id', $film->film_id)
                    ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
                    ->where('pemesanans.status_pemesanan', 'Lunas')
                    ->count();

                $totalTickets = Pemesanan::join('jadwals', 'pemesanans.jadwal_id', '=', 'jadwals.jadwal_id')
                    ->where('jadwals.film_id', $film->film_id)
                    ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
                    ->where('pemesanans.status_pemesanan', 'Lunas')
                    ->withCount('detailPemesanans')
                    ->get()
                    ->sum('detail_pemesanans_count');

                $film->total_revenue = $totalRevenue;
                $film->total_transactions = $totalTransactions;
                $film->total_tickets = $totalTickets;
                $film->average_per_transaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

                return $film;
            })
            ->filter(function($film) {
                return $film->total_revenue > 0; // Only show films with sales
            });

        // Total summary
        $totalRevenue = $films->sum('total_revenue');
        $totalTransactions = $films->sum('total_transactions');
        $totalTickets = $films->sum('total_tickets');

        return view('owner.laporan.film', compact(
            'films',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalTransactions',
            'totalTickets'
        ));
    }

    /**
     * Laporan Per Kasir
     */
    public function laporanKasir(Request $request)
    {
        // Default date range: Last 30 days
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));

        // Convert to Carbon instances
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get all kasirs with their statistics
        $kasirs = User::where('role', 'Kasir')
            ->get()
            ->map(function($kasir) use ($start, $end) {
                // Calculate statistics for offline transactions only
                $totalRevenue = Pemesanan::where('user_id', $kasir->user_id)
                    ->where('jenis_pemesanan', 'Offline')
                    ->whereBetween('tanggal_pemesanan', [$start, $end])
                    ->where('status_pemesanan', 'Lunas')
                    ->sum('total_bayar');

                $totalTransactions = Pemesanan::where('user_id', $kasir->user_id)
                    ->where('jenis_pemesanan', 'Offline')
                    ->whereBetween('tanggal_pemesanan', [$start, $end])
                    ->where('status_pemesanan', 'Lunas')
                    ->count();

                $totalTickets = Pemesanan::where('user_id', $kasir->user_id)
                    ->where('jenis_pemesanan', 'Offline')
                    ->whereBetween('tanggal_pemesanan', [$start, $end])
                    ->where('status_pemesanan', 'Lunas')
                    ->withCount('detailPemesanans')
                    ->get()
                    ->sum('detail_pemesanans_count');

                $kasir->total_revenue = $totalRevenue;
                $kasir->total_transactions = $totalTransactions;
                $kasir->total_tickets = $totalTickets;
                $kasir->average_per_transaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

                return $kasir;
            })
            ->sortByDesc('total_revenue');

        // Total summary
        $totalRevenue = $kasirs->sum('total_revenue');
        $totalTransactions = $kasirs->sum('total_transactions');
        $totalTickets = $kasirs->sum('total_tickets');

        return view('owner.laporan.kasir', compact(
            'kasirs',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalTransactions',
            'totalTickets'
        ));
    }

    /**
     * Laporan Per Periode (Analisis Mendalam)
     */
    public function laporanPeriode(Request $request)
    {
        // Default date range: Last 30 days
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));

        // Convert to Carbon instances
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Daily breakdown
        $dailyData = Pemesanan::selectRaw('DATE(tanggal_pemesanan) as date, 
                SUM(total_bayar) as revenue,
                COUNT(*) as transactions,
                jenis_pemesanan')
            ->whereBetween('tanggal_pemesanan', [$start, $end])
            ->where('status_pemesanan', 'Lunas')
            ->groupBy('date', 'jenis_pemesanan')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        // Process daily data
        $processedDaily = collect();
        foreach($dailyData as $date => $items) {
            $online = $items->where('jenis_pemesanan', 'Online')->first();
            $offline = $items->where('jenis_pemesanan', 'Offline')->first();

            $processedDaily->push((object)[
                'date' => $date,
                'online_revenue' => $online->revenue ?? 0,
                'offline_revenue' => $offline->revenue ?? 0,
                'total_revenue' => ($online->revenue ?? 0) + ($offline->revenue ?? 0),
                'online_transactions' => $online->transactions ?? 0,
                'offline_transactions' => $offline->transactions ?? 0,
                'total_transactions' => ($online->transactions ?? 0) + ($offline->transactions ?? 0),
            ]);
        }

        // Peak day (highest revenue)
        $peakDay = $processedDaily->sortByDesc('total_revenue')->first();

        // Average per day
        $averagePerDay = $processedDaily->avg('total_revenue');

        // Total summary
        $totalRevenue = $processedDaily->sum('total_revenue');
        $totalTransactions = $processedDaily->sum('total_transactions');

        return view('owner.laporan.periode', compact(
            'processedDaily',
            'startDate',
            'endDate',
            'peakDay',
            'averagePerDay',
            'totalRevenue',
            'totalTransactions'
        ));
    }

    /**
     * Export Laporan Film to PDF
     */
    public function exportFilmPDF(Request $request)
    {
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get films data (same logic as laporanFilm)
        $films = Film::select('films.*')
            ->leftJoin('jadwals', 'films.film_id', '=', 'jadwals.film_id')
            ->leftJoin('pemesanans', function($join) use ($start, $end) {
                $join->on('jadwals.jadwal_id', '=', 'pemesanans.jadwal_id')
                    ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
                    ->where('pemesanans.status_pemesanan', 'Lunas');
            })
            ->groupBy('films.film_id')
            ->orderByRaw('SUM(pemesanans.total_bayar) DESC')
            ->get()
            ->map(function($film) use ($start, $end) {
                $totalRevenue = Pemesanan::join('jadwals', 'pemesanans.jadwal_id', '=', 'jadwals.jadwal_id')
                    ->where('jadwals.film_id', $film->film_id)
                    ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
                    ->where('pemesanans.status_pemesanan', 'Lunas')
                    ->sum('pemesanans.total_bayar');

                $totalTransactions = Pemesanan::join('jadwals', 'pemesanans.jadwal_id', '=', 'jadwals.jadwal_id')
                    ->where('jadwals.film_id', $film->film_id)
                    ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
                    ->where('pemesanans.status_pemesanan', 'Lunas')
                    ->count();

                $totalTickets = Pemesanan::join('jadwals', 'pemesanans.jadwal_id', '=', 'jadwals.jadwal_id')
                    ->where('jadwals.film_id', $film->film_id)
                    ->whereBetween('pemesanans.tanggal_pemesanan', [$start, $end])
                    ->where('pemesanans.status_pemesanan', 'Lunas')
                    ->withCount('detailPemesanans')
                    ->get()
                    ->sum('detail_pemesanans_count');

                $film->total_revenue = $totalRevenue;
                $film->total_transactions = $totalTransactions;
                $film->total_tickets = $totalTickets;

                return $film;
            })
            ->filter(function($film) {
                return $film->total_revenue > 0;
            });

        $totalRevenue = $films->sum('total_revenue');
        $totalTransactions = $films->sum('total_transactions');
        $totalTickets = $films->sum('total_tickets');
        $generatedDate = Carbon::now()->format('d M Y H:i');

        $pdf = Pdf::loadView('owner.laporan.film-pdf', compact(
            'films', 'startDate', 'endDate', 'totalRevenue', 
            'totalTransactions', 'totalTickets', 'generatedDate'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-per-film-' . $startDate . '-to-' . $endDate . '.pdf');
    }
}