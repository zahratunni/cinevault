<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';
    protected $primaryKey = 'pembayaran_id';

    protected $fillable = [
        'pemesanan_id',
        'user_id',
        'metode_bayar',
        'nominal_dibayar',
        'tanggal_pembayaran',
        'status_pembayaran',
        // Kolom baru untuk sistem online
        'jenis_pembayaran',
        'metode_online',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // --- RELASI ---

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'pemesanan_id');
    }

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by', 'user_id');
    }
}