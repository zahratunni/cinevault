<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'film_id', 'studio_id', 'tanggal_tayang', 'jam_mulai', 'jam_selesai',
        'harga_reguler', 'status_jadwal', 'created_by'
    ];

    // Jadwal dimiliki oleh satu Film
    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class, 'film_id', 'film_id');
    }

    // Jadwal dimiliki oleh satu Studio
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class, 'studio_id', 'studio_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    // Jadwal memiliki banyak Pemesanan
    public function pemesanans(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'jadwal_id', 'jadwal_id');
    }
}
