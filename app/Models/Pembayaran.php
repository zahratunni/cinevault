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
        'bukti_pembayaran',
        'verified_by',
        'verified_at',
        'rejection_reason',
        'admin_notes',
        // 🔥 KOLOM MIDTRANS
        'snap_token',
        'transaction_id',
        'payment_type',
        'metode_online',
        'jenis_pembayaran',
        'status_verifikasi',
        'catatan_verifikasi'
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
        'verified_at' => 'datetime',
        'nominal_dibayar' => 'decimal:2'
    ];

    // Relasi ke Pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'pemesanan_id');
    }

    // Relasi ke User (Kasir)
    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke Admin yang verifikasi
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by', 'user_id');
    }

    // Scope untuk filter status
    public function scopePending($query)
    {
        return $query->where('status_pembayaran', 'Pending');
    }

    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'Lunas');
    }

    public function scopeGagal($query)
    {
        return $query->where('status_pembayaran', 'Gagal');
    }

    // Helper untuk cek apakah sudah diverifikasi
    public function isVerified()
    {
        return !is_null($this->verified_at);
    }

    // Helper untuk badge status
    public function getStatusBadge()
    {
        $badges = [
            'Pending' => 'warning',
            'Lunas' => 'success',
            'Gagal' => 'danger'
        ];

        return $badges[$this->status_pembayaran] ?? 'secondary';
    }
}