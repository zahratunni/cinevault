<?php

use App\Http\Controllers\FilmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
// 1. FILM CONTROLLER (Menampilkan Detail Film & Jadwal)
Route::get('/film/{id}', [FilmController::class, 'show'])->name('film.show');
// Menampilkan semua film yang sedang tayang
Route::get('/films/playing-now', [FilmController::class, 'playingNow'])->name('films.playingNow');
// Halaman untuk semua film Upcoming
Route::get('/films/upcoming', [FilmController::class, 'upcoming'])->name('films.upcoming');
// 2. BOOKING CONTROLLER (Memilih Kursi & Membuat Pemesanan Awal)
// Mengganti /kursi/{jadwal_id}
Route::get('/booking/{jadwal_id}/kursi', [BookingController::class, 'create'])->name('booking.kursi'); 
// Mengganti /booking/proses
Route::post('/booking/proses', [BookingController::class, 'store'])->name('booking.proses');
// Mengganti /booking/success/{pemesanan_id}
Route::get('/booking/success/{pemesanan_id}', [BookingController::class, 'success'])->name('booking.success');
// 3. PAYMENT CONTROLLER (Menampilkan Form Pembayaran & Memproses Pembayaran)
// Mengganti /payment/{pemesanan_id}
Route::get('/payment/{pemesanan_id}', [PaymentController::class, 'create'])->name('payment.show');
// Mengganti /payment/{pemesanan_id}/process
Route::post('/payment/{pemesanan_id}/process', [PaymentController::class, 'store'])->name('payment.process');
// 4. INVOICE CONTROLLER (Menampilkan Invoice/E-Tiket Final)
// Mengganti /invoice/{pemesanan_id}
Route::get('/invoice/{pemesanan_id}', [InvoiceController::class, 'show'])->name('invoice.show');

