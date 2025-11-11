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
    protected $table = 'users'; 
    protected $primaryKey = 'user_id'; 
    protected $fillable = [
        'username', 
        'email',
        'password',
        'nama_lengkap',
        'no_telepon',
        'role', 
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
       
    ];
    public function pemesanans(): HasMany
    {
       
        return $this->hasMany(Pemesanan::class, 'user_id', 'user_id');
    }
    public function jadwals(): HasMany
    {
       
        return $this->hasMany(Jadwal::class, 'created_by', 'user_id');
    }













    // User (Admin) membuat banyak Catatan Keuangan
    public function pembayarans(): HasMany
    {
        // Relasi HasMany: (Model tujuan, Foreign Key di tabel tujuan, Primary Key di model ini)
        return $this->hasMany(Pembayaran::class, 'user_id', 'user_id');
    }
}