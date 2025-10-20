<?php

use App\Http\Controllers\FilmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFilmController;
use App\Http\Controllers\Admin\AdminJadwalController;
use App\Http\Controllers\Admin\AdminStudioController;
use App\Http\Controllers\Admin\AdminKasirController;
use App\Http\Controllers\Admin\AdminPelangganController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\VerifikasiPembayaranController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\KasirPembayaranController;
use App\Http\Controllers\Kasir\KasirPemesananController;
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
    Route::post('/payment/{pemesanan_id}/process', [PaymentController::class, 'process'])->name('payment.process');

    // ✅ Tambahan route untuk halaman QR + waiting + pengecekan status
    Route::get('/payment/{pemesanan_id}/waiting', [PaymentController::class, 'waiting'])->name('payment.waiting');
    Route::get('/payment/{pemesanan_id}/check-status', [PaymentController::class, 'checkStatus'])->name('payment.checkStatus');

    // Invoice
    Route::get('/invoice/{pemesanan_id}', [InvoiceController::class, 'show'])->name('invoice.show');

    // Profile & Riwayat
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile.index');
    Route::get('/profile/riwayat', [CustomerController::class, 'riwayat'])->name('profile.riwayat');
    Route::post('/profile/update', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/update-password', [CustomerController::class, 'updatePassword'])->name('profile.password.update');
});


/*
|-------  -------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Films
    Route::resource('films', AdminFilmController::class);

    // Profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // Jadwal, Studio, Kasir
    Route::resource('jadwals', AdminJadwalController::class);
    Route::resource('studios', AdminStudioController::class);
    Route::resource('kasirs', AdminKasirController::class);

    // Pelanggan
    Route::get('/pelanggans', [AdminPelangganController::class, 'index'])->name('pelanggans.index');
    Route::get('/pelanggans/{pelanggan}', [AdminPelangganController::class, 'show'])->name('pelanggans.show');
    Route::delete('/pelanggans/{pelanggan}', [AdminPelangganController::class, 'destroy'])->name('pelanggans.destroy'); 

    // ✅ Verifikasi Pembayaran
    Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
        Route::get('/', [VerifikasiPembayaranController::class, 'index'])->name('index');
        Route::get('/{id}', [VerifikasiPembayaranController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [VerifikasiPembayaranController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [VerifikasiPembayaranController::class, 'reject'])->name('reject');
    });
});


/*
|--------------------------------------------------------------------------
| Kasir Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    
    // Pemesanan Offline
    Route::get('/pemesanan', [KasirPemesananController::class, 'index'])->name('pemesanan.index');
    Route::post('/pemesanan/store', [KasirPemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('/kursi-available/{jadwal_id}', [KasirPemesananController::class, 'getKursiAvailable'])->name('kursi.available');
    
    // Pembayaran Offline
    Route::get('/pembayaran/{pemesanan_id}', [KasirPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/{pemesanan_id}/store', [KasirPembayaranController::class, 'store'])->name('pembayaran.store');
});


/*
|--------------------------------------------------------------------------
| Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', function () {
        return view('owner.dashboard');
    })->name('dashboard');
    
});

/*
|--------------------------------------------------------------------------
| Logout (Semua Role yang Login)
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');