<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini untuk relasi

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // --- 1. KONFIGURASI TABEL & PRIMARY KEY ---
    protected $table = 'users'; 
    protected $primaryKey = 'user_id'; // Menggunakan PK non-standar Anda
   

    /**
     * The attributes that are mass assignable.
     * Disesuaikan dengan kolom yang ada di tabel 'users'.
     */
    protected $fillable = [
        'username', // Diganti dari 'name'
        'email',
        'password',
        'nama_lengkap',
        'no_telepon',
        'role', // Termasuk kolom role
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Jika perlu casting tambahan, tambahkan di sini
    ];


    // --- 2. RELATIONS (Hubungan ke Tabel Transaksi) ---

    // User (Customer) membuat banyak Pemesanan
    public function pemesanans(): HasMany
    {
        // Relasi HasMany: (Model tujuan, Foreign Key di tabel tujuan, Primary Key di model ini)
        return $this->hasMany(Pemesanan::class, 'user_id', 'user_id');
    }

    // User (Kasir) memvalidasi banyak Pembayaran
    public function jadwals(): HasMany
    {
        // Relasi HasMany: (Model tujuan, Foreign Key di tabel tujuan, Primary Key di model ini)
        return $this->hasMany(Jadwal::class, 'created_by', 'user_id');
    }

    // User (Admin) membuat banyak Catatan Keuangan
    public function pembayarans(): HasMany
    {
        // Relasi HasMany: (Model tujuan, Foreign Key di tabel tujuan, Primary Key di model ini)
        return $this->hasMany(Pembayaran::class, 'user_id', 'user_id');
    }
}