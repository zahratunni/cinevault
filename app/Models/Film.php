<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Film extends Model
{
    use HasFactory;

    protected $table = 'films';
    protected $primaryKey = 'film_id';

     // Kolom yang bisa diisi
    protected $fillable = [
        'judul', 'sinopsis', 'durasi_menit', 'trailer_url', 'genre', 'produser', 
        'sutradara', 'penulis', 'produksi', 'cast_list', 'rating', 'poster_url', 
        'status_tayang'
    ];

    // Film memiliki banyak Jadwal tayang
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'film_id', 'film_id');
    }
}
