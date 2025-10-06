<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Studio extends Model
{
    use HasFactory;
    protected $table = 'studios';
    protected $primaryKey = 'studio_id';

     protected $fillable = [
        'nama_studio', 'kapasitas', 'created_by', 'status_studio'
    ];
   

    // Studio memiliki banyak Kursi
    public function kursis(): HasMany
    {
        return $this->hasMany(Kursi::class, 'studio_id', 'studio_id');
    }
    
    // Studio memiliki banyak Jadwal tayang
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'studio_id', 'studio_id');
    }

    public function createdBy(): BelongsTo
    {
    // Model hanya bilang: 'created_by' adalah Foreign Key yang merujuk ke User.
    return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}
