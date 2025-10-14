<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminKasirController extends Controller
{
    /**
     * Display a listing of kasir
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $kasirs = User::where('role', 'Kasir')
            ->when($search, function($query, $search) {
                return $query->where('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('nama_lengkap', 'like', "%{$search}%");
            })
            ->withCount(['pembayarans', 'jadwals'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.kasirs.index', compact('kasirs', 'search'));
    }

    /**
     * Show the form for creating a new kasir
     */
    public function create()
    {
        return view('admin.kasirs.create');
    }

    /**
     * Store a newly created kasir
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'nama_lengkap' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'Kasir';

        User::create($validated);

        return redirect()->route('admin.kasirs.index')
            ->with('success', 'Kasir berhasil ditambahkan!');
    }

    /**
     * Display the specified kasir
     */
    public function show(User $kasir)
    {
        // Pastikan user adalah kasir
        if ($kasir->role !== 'Kasir') {
            abort(404);
        }

        $kasir->load(['pembayarans.pemesanan.jadwal.film', 'jadwals.film.studio']);
        
        // Recent transactions
        $recentTransactions = $kasir->pembayarans()
            ->with(['pemesanan.user', 'pemesanan.jadwal.film'])
            ->orderBy('tanggal_pembayaran', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.kasirs.show', compact('kasir', 'recentTransactions'));
    }

    /**
     * Show the form for editing the specified kasir
     */
    public function edit(User $kasir)
    {
        // Pastikan user adalah kasir
        if ($kasir->role !== 'Kasir') {
            abort(404);
        }

        return view('admin.kasirs.edit', compact('kasir'));
    }

    /**
     * Update the specified kasir
     */
    public function update(Request $request, User $kasir)
    {
        // Pastikan user adalah kasir
        if ($kasir->role !== 'Kasir') {
            abort(404);
        }

        $validated = $request->validate([
            'email' => 'required|email|max:100|unique:users,email,' . $kasir->user_id . ',user_id',
            'nama_lengkap' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $kasir->update($validated);

        return redirect()->route('admin.kasirs.index')
            ->with('success', 'Data kasir berhasil diupdate!');
    }

    /**
     * Remove the specified kasir
     */
    public function destroy(User $kasir)
    {
        // Pastikan user adalah kasir
        if ($kasir->role !== 'Kasir') {
            abort(404);
        }

        // Check if kasir has handled transactions
        $transactionCount = $kasir->pembayarans()->count();
        
        if ($transactionCount > 0) {
            return redirect()->route('admin.kasirs.index')
                ->with('error', 'Kasir tidak dapat dihapus karena telah menangani ' . $transactionCount . ' transaksi! Untuk keamanan data, kasir dengan riwayat transaksi tidak dapat dihapus.');
        }

        // Check if kasir has created jadwals
        $jadwalCount = $kasir->jadwals()->count();
        
        if ($jadwalCount > 0) {
            return redirect()->route('admin.kasirs.index')
                ->with('error', 'Kasir tidak dapat dihapus karena telah membuat ' . $jadwalCount . ' jadwal!');
        }

        $kasir->delete();

        return redirect()->route('admin.kasirs.index')
            ->with('success', 'Kasir berhasil dihapus!');
    }
}