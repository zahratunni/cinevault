<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('auth.login');
    }

    /**
     * Process login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'no_telepon' => 'required|string',
            'password' => 'required',
        ]);

        // Login menggunakan nomor telepon
        if (Auth::attempt(['no_telepon' => $request->no_telepon, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            return $this->redirectBasedOnRole();
        }

        return back()->withErrors([
            'no_telepon' => 'Nomor telepon atau password salah.',
        ])->withInput($request->only('no_telepon'));
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Process register
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_telepon' => 'required|string|max:15',
        ]);

        // Buat user baru dengan role Customer
        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon,
            'role' => 'Customer',
        ]);

        // TIDAK auto login, redirect ke halaman login
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }

    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole()
    {
        $user = Auth::user();
        
        // Untuk Customer: redirect ke intended URL atau home
        if ($user->role === 'Customer') {
            return redirect()->intended(route('home'));
        }
        
        // Untuk role lain: ke dashboard masing-masing
        switch ($user->role) {
            case 'Admin':
                return redirect()->route('admin.dashboard');
            case 'Kasir':
                return redirect()->route('kasir.dashboard');
            case 'Owner':
                return redirect()->route('owner.dashboard');
            default:
                return redirect()->route('home');
        }
    }
}