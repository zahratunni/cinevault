<?php

use App\Http\Controllers\FilmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFilmController;
use App\Http\Controllers\Admin\AdminJadwalController;
use App\Http\Controllers\Admin\AdminStudioController;
use App\Http\Controllers\Admin\AdminKasirController;
use App\Http\Controllers\Admin\AdminOwnerController;
use App\Http\Controllers\Admin\AdminPelangganController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\KasirProfileController;
use App\Http\Controllers\Kasir\KasirPembayaranController;
use App\Http\Controllers\Kasir\KasirPemesananController;
use App\Http\Controllers\Kasir\KasirTiketController;
use App\Http\Controllers\Kasir\KasirMidtransController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\OwnerProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Public - No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/films', [FilmController::class, 'index'])->name('films.index');
Route::get('/films/playing-now', [FilmController::class, 'playingNow'])->name('films.playingNow');
Route::get('/films/upcoming', [FilmController::class, 'upcoming'])->name('films.upcoming');
Route::get('/film/{id}', [FilmController::class, 'show'])->name('film.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Midtrans Callbacks (Public - No Auth, No CSRF)
|--------------------------------------------------------------------------
*/
Route::post('/payment/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');
Route::post('/kasir/midtrans/callback', [KasirMidtransController::class, 'callback'])->name('kasir.midtrans.callback');

/*
|--------------------------------------------------------------------------
| OWNER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Owner'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-pdf', [OwnerDashboardController::class, 'exportPDF'])->name('dashboard.export-pdf');
    
    // Profile (Read Only)
    Route::get('/profile', [OwnerProfileController::class, 'index'])->name('profile.index');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // Resource Controllers
    Route::resource('films', AdminFilmController::class);
    Route::resource('jadwals', AdminJadwalController::class);
    Route::resource('studios', AdminStudioController::class);
    Route::resource('kasirs', AdminKasirController::class);
    Route::resource('owners', AdminOwnerController::class);

    

    // Pelanggan Management
    Route::get('/pelanggans', [AdminPelangganController::class, 'index'])->name('pelanggans.index');
    Route::get('/pelanggans/{pelanggan}', [AdminPelangganController::class, 'show'])->name('pelanggans.show');
    Route::delete('/pelanggans/{pelanggan}', [AdminPelangganController::class, 'destroy'])->name('pelanggans.destroy');
});

/*
|--------------------------------------------------------------------------
| KASIR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    
    // Pemesanan Offline (Buat Baru)
    Route::get('/pemesanan', [KasirPemesananController::class, 'index'])->name('pemesanan.index');
    Route::post('/pemesanan/store', [KasirPemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('/kursi-available/{jadwal_id}', [KasirPemesananController::class, 'getKursiAvailable'])->name('kursi.available');
    
    // Kelola Pemesanan Offline
    Route::get('/kelola-pemesanan', [KasirPemesananController::class, 'kelolaPemesanan'])->name('kelola.pemesanan');
    Route::get('/kelola-pemesanan/{pemesanan_id}', [KasirPemesananController::class, 'detailPemesanan'])->name('kelola.detail');
    Route::post('/kelola-pemesanan/{pemesanan_id}/cancel', [KasirPemesananController::class, 'cancelPemesanan'])->name('kelola.cancel');
    
    // Pembayaran Offline
    Route::get('/pembayaran/{pemesanan_id}', [KasirPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/{pemesanan_id}/store', [KasirPembayaranController::class, 'store'])->name('pembayaran.store');
    Route::post('/pembayaran/{pemesanan_id}/confirm-tunai', [KasirPembayaranController::class, 'confirmTunai'])->name('pembayaran.confirmTunai');
    Route::get('/pembayaran/{pemesanan_id}/midtrans', [KasirPembayaranController::class, 'createMidtrans'])->name('pembayaran.qris');
    Route::get('/pembayaran/{pemesanan_id}/check-status', [KasirPembayaranController::class, 'checkStatus'])->name('pembayaran.checkStatus');
    
    // Midtrans untuk Kasir
    Route::get('/midtrans/create/{pemesanan_id}', [KasirMidtransController::class, 'createTransaction'])->name('midtrans.create');
    Route::get('/midtrans/finish/{pemesanan_id}', [KasirMidtransController::class, 'finish'])->name('midtrans.finish');
    
    // ⭐ MANUAL CALLBACK untuk Testing di Localhost
    Route::get('/midtrans/manual-callback/{pemesanan_id}', [KasirMidtransController::class, 'manualCallback'])->name('midtrans.manualCallback');
    
    // Cetak Tiket
    Route::get('/search-tiket', [KasirTiketController::class, 'search'])->name('tiket.search');
    Route::post('/cari-tiket', [KasirTiketController::class, 'cari'])->name('tiket.cari');
    Route::get('/tiket/{pemesanan_id}', [KasirTiketController::class, 'show'])->name('tiket.show');
    
    // Profile
    Route::get('/profile', [KasirProfileController::class, 'index'])->name('profile.index');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Customer'])->group(function () {
    // Booking
    Route::get('/booking/{jadwal_id}/kursi', [BookingController::class, 'create'])->name('booking.kursi');
    //midtrans di kursi
    Route::post('/booking/proses', [BookingController::class, 'store'])->name('booking.store');
    // Midtrans Payment di riwayat transakasi
    Route::get('/payment/midtrans/{pemesanan_id}', [MidtransController::class, 'createTransaction'])->name('midtrans.create');
    Route::get('/payment/midtrans/finish/{pemesanan_id}', [MidtransController::class, 'finish'])->name('midtrans.finish');
    Route::get('/payment/confirm/{pemesanan_id}', [MidtransController::class, 'manualCallback'])->name('midtrans.confirm');

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
| Testing Routes (REMOVE IN PRODUCTION!)
|--------------------------------------------------------------------------
*/
Route::get('/test-midtrans-success/{pemesanan_id}', function($pemesanan_id) {
    $pembayaran = \App\Models\Pembayaran::where('pemesanan_id', $pemesanan_id)->first();
    $pemesanan = \App\Models\Pemesanan::find($pemesanan_id);
    
    if (!$pembayaran || !$pemesanan) {
        return redirect()->route('home')->with('error', 'Pemesanan tidak ditemukan');
    }
    
    $pembayaran->update([
        'status_pembayaran' => 'Lunas',
        'status_verifikasi' => 'approved',
        'verified_at' => now(),
        'payment_type' => 'gopay',
        'tanggal_pembayaran' => now(),
    ]);
    
    $pemesanan->update(['status_pemesanan' => 'Lunas']);
    
    return redirect()->route('invoice.show', $pemesanan_id)
        ->with('success', 'Pembayaran berhasil dikonfirmasi!');
})->middleware('auth');

Route::get('/test-callback-kasir/{pemesanan_id}', function($pemesanan_id) {
    $pembayaran = \App\Models\Pembayaran::where('pemesanan_id', $pemesanan_id)->first();
    
    if (!$pembayaran) {
        return "Pembayaran tidak ditemukan";
    }
    
    $pemesanan = $pembayaran->pemesanan;
    
    $pembayaran->update([
        'status_pembayaran' => 'Lunas',
        'status_verifikasi' => 'approved',
        'verified_at' => now(),
        'payment_type' => 'gopay',
        'tanggal_pembayaran' => now(),
    ]);
    
    $pemesanan->update(['status_pemesanan' => 'Lunas']);
    
    return redirect()->route('kasir.kelola.pemesanan')
        ->with('success', 'Pembayaran berhasil dikonfirmasi (TEST MODE)');
})->middleware('auth');