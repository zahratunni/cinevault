<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Display admin profile
     */
    public function index()
    {
        $admin = Auth::user();
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update admin profile
     */
    public function update(Request $request)
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'email' => 'required|email|max:100|unique:users,email,' . $admin->user_id . ',user_id',
            'nama_lengkap' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
        ]);

        $admin->update($validated);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile berhasil diupdate!');
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password lama tidak sesuai!'])
                ->withInput();
        }

        // Update password
        $admin->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Password berhasil diubah!');
    }
}