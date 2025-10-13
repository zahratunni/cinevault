<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'film_id', 'studio_id', 'tanggal_tayang', 'jam_mulai', 'jam_selesai',
        'harga_reguler', 'status_jadwal', 'created_by'
    ];

    // Ini memungkinkan kita menggunakan $jadwal->is_purchasable di View
    protected $appends = ['is_purchasable']; 


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

     public function getIsPurchasableAttribute(): bool
    {
        $now = Carbon::now();
        
        // Gabungkan tanggal tayang dan jam mulai untuk mendapatkan waktu tayang pasti (H)
        $showtime = Carbon::parse($this->tanggal_tayang . ' ' . $this->jam_mulai);

        // Batas waktu mulai pembelian: Jam 00:00, 1 hari sebelum tanggal tayang (H-1)
        $purchaseStartTime = $showtime->copy()->subDay()->startOfDay(); 
        
        // Batas waktu akhir pembelian: Tepat pada jam mulai tayang
        $purchaseEndTime = $showtime; 
        
        // Cek apakah waktu sekarang berada di rentang yang diizinkan
        // false berarti $purchaseEndTime tidak inklusif (pembelian ditutup tepat pada jam mulai)
        if ($this->status_jadwal !== 'Active') {
            return false;
        }
        return $now->between($purchaseStartTime, $purchaseEndTime, false);
    }

    public function getIsFullAttribute(): bool
{
    $totalKursi = $this->studio->kursis()->count(); // total kursi di studio
    $kursiTerpesan = $this->pemesanans()
        ->whereIn('status_pemesanan', ['Lunas', 'Menunggu Bayar'])
        ->count();

    return $kursiTerpesan >= $totalKursi;
}

}
