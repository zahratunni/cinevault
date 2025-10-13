<?php

use App\Http\Controllers\FilmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminFilmController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Guest Routes (Tanpa Login)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/film/{id}', [FilmController::class, 'show'])->name('film.show');
Route::get('/films/playing-now', [FilmController::class, 'playingNow'])->name('films.playingNow');
Route::get('/films/upcoming', [FilmController::class, 'upcoming'])->name('films.upcoming');
Route::get('/films', [FilmController::class, 'index'])->name('films.index');

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
    Route::post('/booking/proses', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/success/{pemesanan_id}', [BookingController::class, 'success'])->name('booking.success');
    
    // Payment
    Route::get('/payment/{pemesanan_id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{pemesanan_id}/process', [PaymentController::class, 'store'])->name('payment.process');
    
    // Invoice
    Route::get('/invoice/{pemesanan_id}', [InvoiceController::class, 'show'])->name('invoice.show');
    
    // Profile & Riwayat
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile.index');
    Route::get('/profile/riwayat', [CustomerController::class, 'riwayat'])->name('profile.riwayat');
    Route::post('/profile/update', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/update-password', [CustomerController::class, 'updatePassword'])->name('profile.password.update');
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

// Ganti route admin dashboard yang lama dengan ini:
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('films', AdminFilmController::class);
    Route::get('/jadwals', function() { return 'Coming Soon'; })->name('jadwals.index');
    Route::get('/studios', function() { return 'Coming Soon'; })->name('studios.index');
    Route::get('/kasirs', function() { return 'Coming Soon'; })->name('kasirs.index');
    Route::get('/pelanggans', function() { return 'Coming Soon'; })->name('pelanggans.index');
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