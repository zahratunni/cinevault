<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pemesanan;
use Carbon\Carbon;

class CancelExpiredBookings extends Command
{
    protected $signature = 'bookings:cancel-expired';
    protected $description = 'Cancel all bookings that expired (unpaid after 10 minutes)';

    public function handle()
    {
        $expiredTime = Carbon::now()->subMinutes(10);
        
        // Cari semua booking yang pending dan sudah lewat 10 menit
        $expiredBookings = Pemesanan::where('status_pemesanan', 'Menunggu Bayar')
            ->where('tanggal_pemesanan', '<=', $expiredTime)
            ->get();

        $cancelledCount = 0;

        foreach ($expiredBookings as $pemesanan) {
            $pemesanan->update(['status_pemesanan' => 'Kadaluarsa']);

            if ($pemesanan->pembayaran) {
                $pemesanan->pembayaran->update([
                    'status_pembayaran' => 'Kadaluarsa',
                    'status_verifikasi' => 'rejected'
                ]);
            }

            $cancelledCount++;
            $this->info("✓ Cancelled: {$pemesanan->kode_transaksi}");
        }

        $this->info("Total cancelled: {$cancelledCount} bookings");
        
        \Log::info('Expired bookings cleanup', [
            'cancelled_count' => $cancelledCount,
            'executed_at' => now()
        ]);

        return 0;
    }
}