<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class CustomerController extends Controller
{
    public function profile()
{
    $user = auth()->user(); // ambil data user yang sedang login

    return view('profile.index', compact('user'));
}

public function riwayat()
{
    $user = auth()->user();

    // Ambil semua riwayat pemesanan milik user ini
    $riwayat = Pemesanan::with(['jadwal', 'pembayaran'])
                ->where('user_id', $user->user_id)
                ->orderBy('tanggal_pemesanan', 'desc')
                ->get();

    return view('profile.riwayat', compact('riwayat'));
}

public function updateProfile(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'username' => 'required|string|max:50|unique:users,username,' . $user->user_id . ',user_id',
        'nama_lengkap' => 'nullable|string|max:100',
        'no_telepon' => 'nullable|string|max:15',
        'email' => 'required|email|max:100|unique:users,email,' . $user->user_id . ',user_id',
    ]);

    $user->update($validated);

    return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
}

public function updatePassword(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
    }

    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    return back()->with('success', 'Password berhasil diperbarui!');
}


}
