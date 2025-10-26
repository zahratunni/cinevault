<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanans';
    protected $primaryKey = 'pemesanan_id';

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'kode_transaksi',
        'jenis_pemesanan',
        'status_pemesanan',
        'harga_dasar_total',
        'total_bayar',
        'tanggal_pemesanan',
        'tiket_dicetak_at',    // ← Sudah ada
        'dicetak_oleh',
    ];

    // ✅ TAMBAHKAN CAST INI
    protected $casts = [
        'tanggal_pemesanan' => 'datetime',
        'tiket_dicetak_at' => 'datetime',  // ← PENTING!
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal()
{
    return $this->belongsTo(Jadwal::class, 'jadwal_id', 'jadwal_id');
}
    public function detailPemesanans()
    {
        return $this->hasMany(DetailPemesanan::class, 'pemesanan_id', 'pemesanan_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pemesanan_id', 'pemesanan_id');
    }

    public function kasirPencetak()
    {
        return $this->belongsTo(User::class, 'dicetak_oleh');
    }
}