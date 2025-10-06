<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kursi extends Model
{
    use HasFactory;

    protected $table = 'kursis';
    protected $primaryKey = 'kursi_id';

    protected $fillable = [
        'studio_id', 'kode_kursi', 'baris', 'nomor_kursi'
    ];

    // Kursi dimiliki oleh satu Studio
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class, 'studio_id', 'studio_id');
    }

    // Kursi muncul di banyak Detail Pemesanan
    public function detailPemesanans(): HasMany
    {
        return $this->hasMany(DetailPemesanan::class, 'kursi_id', 'kursi_id');
    }
}
