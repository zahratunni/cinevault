<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPelangganController extends Controller
{
    /**
     * Display a listing of pelanggan (customers)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'newest'); // newest, oldest, most_active, top_spender
        
        $pelanggans = User::where('role', 'Customer')
            ->when($search, function($query, $search) {
                return $query->where('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('nama_lengkap', 'like', "%{$search}%");
            })
            ->withCount([
                'pemesanans',
                'pemesanans as total_spending' => function($query) {
                    $query->select(DB::raw('COALESCE(SUM(total_bayar), 0)'))
                          ->where('status_pemesanan', 'Lunas');
                }
            ])
            ->when($sort == 'newest', function($query) {
                return $query->orderBy('created_at', 'desc');
            })
            ->when($sort == 'oldest', function($query) {
                return $query->orderBy('created_at', 'asc');
            })
            ->when($sort == 'most_active', function($query) {
                return $query->orderBy('pemesanans_count', 'desc');
            })
            ->when($sort == 'top_spender', function($query) {
                return $query->orderBy('total_spending', 'desc');
            })
            ->paginate(15);
        
        // Stats for dashboard
        $totalCustomers = User::where('role', 'Customer')->count();
        $activeCustomers = User::where('role', 'Customer')
            ->whereHas('pemesanans', function($query) {
                $query->where('status_pemesanan', 'Lunas')
                      ->where('tanggal_pemesanan', '>=', now()->subMonths(3));
            })
            ->count();
        
        return view('admin.pelanggans.index', compact('pelanggans', 'search', 'sort', 'totalCustomers', 'activeCustomers'));
    }

    /**
     * Display the specified pelanggan
     */
    public function show(User $pelanggan)
    {
        // Pastikan user adalah customer
        if ($pelanggan->role !== 'Customer') {
            abort(404);
        }

        $pelanggan->load(['pemesanans.jadwal.film', 'pemesanans.jadwal.studio', 'pemesanans.pembayaran']);
        
        // Statistics
        $totalBookings = $pelanggan->pemesanans->count();
        $completedBookings = $pelanggan->pemesanans->where('status_pemesanan', 'Lunas')->count();
        $totalSpending = $pelanggan->pemesanans->where('status_pemesanan', 'Lunas')->sum('total_bayar');
        $pendingBookings = $pelanggan->pemesanans->where('status_pemesanan', 'Menunggu Bayar')->count();
        
        // Recent bookings
        $recentBookings = $pelanggan->pemesanans()
            ->with(['jadwal.film', 'jadwal.studio', 'pembayaran'])
            ->orderBy('tanggal_pemesanan', 'desc')
            ->limit(10)
            ->get();
        
        // Favorite genre
        $favoriteGenre = $pelanggan->pemesanans()
            ->join('jadwals', 'pemesanans.jadwal_id', '=', 'jadwals.jadwal_id')
            ->join('films', 'jadwals.film_id', '=', 'films.film_id')
            ->where('pemesanans.status_pemesanan', 'Lunas')
            ->select('films.genre', DB::raw('COUNT(*) as total'))
            ->groupBy('films.genre')
            ->orderBy('total', 'desc')
            ->first();
        
        return view('admin.pelanggans.show', compact(
            'pelanggan', 
            'totalBookings', 
            'completedBookings', 
            'totalSpending', 
            'pendingBookings',
            'recentBookings',
            'favoriteGenre'
        ));
    }

    /**
     * Remove the specified pelanggan
     */
    public function destroy(User $pelanggan)
    {
        // Pastikan user adalah customer
        if ($pelanggan->role !== 'Customer') {
            abort(404);
        }

        // Check if customer has any bookings
        $bookingCount = $pelanggan->pemesanans()->count();
        
        if ($bookingCount > 0) {
            return redirect()->route('admin.pelanggans.index')
                ->with('error', 'Pelanggan tidak dapat dihapus karena memiliki riwayat transaksi! (' . $bookingCount . ' booking). Untuk keamanan data dan audit trail, customer dengan transaksi tidak boleh dihapus.');
        }

        // Safe to delete - no transaction history
        $username = $pelanggan->username;
        $pelanggan->delete();

        return redirect()->route('admin.pelanggans.index')
            ->with('success', 'Pelanggan "' . $username . '" berhasil dihapus!');
    }
}