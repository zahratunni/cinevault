<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';
    protected $primaryKey = 'pembayaran_id';

    protected $fillable = [
        'pemesanan_id', 'metode_bayar', 'nominal_dibayar', 'tanggal_pembayaran', 
        'status_pembayaran', 'user_id' // user_id di sini adalah Kasir
    ];

    // --- RELASI BELONGS TO (Foreign Key) ---

    /**
     * Pembayaran dimiliki oleh satu Pemesanan (WAJIB diisi).
     */
    public function pemesanan(): BelongsTo
    {
        // FK di tabel ini adalah 'id_pemesanan', PK di model tujuan juga 'id_pemesanan'
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'pemesanan_id');
    }

    /**
     * Pembayaran divalidasi oleh satu User (Kasir) (Bisa nullable).
     */
    public function kasir(): BelongsTo
    {
        // FK di tabel ini adalah 'id_kasir', PK di model tujuan adalah 'id_user'
        return $this->belongsTo(User::class, 'user-id', 'user_id');
    }
}
