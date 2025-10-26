<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Pemesanan;
use Carbon\Carbon;

class CancelExpiredBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pemesananId;

    /**
     * Create a new job instance.
     */
    public function __construct($pemesananId)
    {
        $this->pemesananId = $pemesananId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $pemesanan = Pemesanan::with('pembayaran')->find($this->pemesananId);

        // Jika pemesanan tidak ditemukan, skip
        if (!$pemesanan) {
            \Log::warning('Pemesanan not found for auto-cancel', ['id' => $this->pemesananId]);
            return;
        }

        // ✅ Cek apakah masih pending
        if ($pemesanan->status_pemesanan === 'Menunggu Bayar') {
            
            // Update status pemesanan jadi Kadaluarsa
            $pemesanan->update([
                'status_pemesanan' => 'Kadaluarsa'
            ]);

            // Update status pembayaran (jika ada)
            if ($pemesanan->pembayaran) {
                $pemesanan->pembayaran->update([
                    'status_pembayaran' => 'Kadaluarsa',
                    'status_verifikasi' => 'rejected'
                ]);
            }

            // Log untuk tracking
            \Log::info('🚫 Booking Auto-Cancelled (Expired)', [
                'pemesanan_id' => $pemesanan->pemesanan_id,
                'kode_transaksi' => $pemesanan->kode_transaksi,
                'user_id' => $pemesanan->user_id,
                'created_at' => $pemesanan->tanggal_pemesanan,
                'cancelled_at' => now()->toDateTimeString()
            ]);
        } else {
            // Jika sudah lunas/dibatalkan manual, skip
            \Log::info('Booking already processed, skip auto-cancel', [
                'pemesanan_id' => $pemesanan->pemesanan_id,
                'status' => $pemesanan->status_pemesanan
            ]);
        }
    }
}