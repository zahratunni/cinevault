<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPemesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pemesanans';
    protected $primaryKey = 'detail_id';

    protected $fillable = [
        'pemesanan_id', 'kursi_id', 'harga_per_kursi'
    ];

    // Detail dimiliki oleh satu Pemesanan
    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'pemesanan_id');
    }

    // Detail terkait dengan satu Kursi
    public function kursi(): BelongsTo
    {
        return $this->belongsTo(Kursi::class, 'kursi_id', 'kursi_id');
    }
}
