<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;



class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanans';
    protected $primaryKey = 'pemesanan_id';

    protected $fillable = [
        'user_id', 'jadwal_id', 'kode_transaksi', 'harga_dasar_total', 
        'total_bayar', 'jenis_pemesanan', 'status_pemesanan', 'tanggal_pemesanan'
    ];
    // --- RELASI BELONGS TO (Foreign Key) ---

    // 1. Pemesanan dimiliki oleh satu User (Customer)
    public function user(): BelongsTo
    {
        // FK: id_customer menunjuk ke PK id_user di Model User
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // 2. Pemesanan terkait dengan satu Jadwal tayang
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'jadwal_id');
    }

    // --- RELASI HAS MANY / HAS ONE (Primary Key) ---

    // 3. Pemesanan memiliki banyak Detail Pemesanan (untuk setiap kursi)
    public function detailPemesanans(): HasMany
    {
        return $this->hasMany(DetailPemesanan::class, 'pemesanan_id', 'pemesanan_id');
    }
    
    // 4. Pemesanan memiliki satu Pembayaran (relasi 1:1)
    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'pemesanan_id', 'pemesanan_id');
    }
}
