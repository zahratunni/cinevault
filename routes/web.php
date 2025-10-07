<?php

use App\Http\Controllers\FilmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Tanpa Login)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/film/{id}', [FilmController::class, 'show'])->name('film.show');
Route::get('/films/playing-now', [FilmController::class, 'playingNow'])->name('films.playingNow');
Route::get('/films/upcoming', [FilmController::class, 'upcoming'])->name('films.upcoming');

/*
|--------------------------------------------------------------------------
| Auth Routes (Login & Register)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Customer Routes (Perlu Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Customer'])->group(function () {
    // Booking
    Route::get('/booking/{jadwal_id}/kursi', [BookingController::class, 'create'])->name('booking.kursi'); 
    Route::post('/booking/proses', [BookingController::class, 'store'])->name('booking.proses');
    Route::get('/booking/success/{pemesanan_id}', [BookingController::class, 'success'])->name('booking.success');
    
    // Payment
    Route::get('/payment/{pemesanan_id}', [PaymentController::class, 'create'])->name('payment.show');
    Route::post('/payment/{pemesanan_id}/process', [PaymentController::class, 'store'])->name('payment.process');
    
    // Invoice
    Route::get('/invoice/{pemesanan_id}', [InvoiceController::class, 'show'])->name('invoice.show');
    
    // Profile & Riwayat
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile.index');
    
    Route::get('/profile/riwayat', function () {
        return view('profile.riwayat');
    })->name('profile.riwayat');
});

/*
|--------------------------------------------------------------------------
| Logout (Semua Role yang Login)
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Kasir Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', function () {
        return view('kasir.dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Owner Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', function () {
        return view('owner.dashboard');
    })->name('dashboard');
});