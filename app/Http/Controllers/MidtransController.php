<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Generate Snap Token dan redirect ke Midtrans
     */
    public function createTransaction($pemesanan_id)
    {
        $pemesanan = Pemesanan::with(['jadwal.film', 'jadwal.studio', 'detailPemesanans.kursi', 'user'])
            ->findOrFail($pemesanan_id);

        // Cek kepemilikan
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini.');
        }

        // Cek status
        if ($pemesanan->status_pemesanan === 'Lunas') {
            return redirect()->route('invoice.show', $pemesanan_id)
                ->with('info', 'Pemesanan sudah lunas.');
        }

        // Generate unique transaction ID
        $transaction_id = 'ORDER-' . $pemesanan->pemesanan_id . '-' . time();

        // Buat atau update pembayaran
        $pembayaran = $pemesanan->pembayaran;
        
        if (!$pembayaran) {
            $pembayaran = Pembayaran::create([
                'pemesanan_id' => $pemesanan->pemesanan_id,
                'user_id' => auth()->id(),
                'metode_bayar' => 'Online',
                'nominal_dibayar' => $pemesanan->total_bayar,
                'tanggal_pembayaran' => now(),
                'status_pembayaran' => 'Pending',
                'transaction_id' => $transaction_id,
                'jenis_pembayaran' => 'Online',
                'status_verifikasi' => 'pending',
            ]);
        } else {
            $pembayaran->update([
                'transaction_id' => $transaction_id,
                'status_pembayaran' => 'Pending',
                'status_verifikasi' => 'pending',
            ]);
        }

        // Siapkan data untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $transaction_id,
                'gross_amount' => (int) $pemesanan->total_bayar,
            ],
            'customer_details' => [
                'first_name' => $pemesanan->user->name,
                'email' => $pemesanan->user->email,
                'phone' => $pemesanan->user->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $pemesanan->jadwal->jadwal_id,
                    'price' => (int) $pemesanan->jadwal->harga_reguler,
                    'quantity' => $pemesanan->detailPemesanans->count(),
                    'name' => $pemesanan->jadwal->film->judul . ' - ' . $pemesanan->jadwal->studio->nama_studio,
                ]
            ],
            'enabled_payments' => [
                'gopay', 'shopeepay', 'other_qris', // E-Wallet & QRIS
                'bca_va', 'bni_va', 'bri_va', 'permata_va', // Virtual Account
                'echannel', // Mandiri Bill
                'credit_card' // Kartu Kredit
            ],
            'callbacks' => [
                'finish' => route('midtrans.finish', $pemesanan_id)
            ]
        ];

        try {
            // Generate Snap Token
            $snapToken = Snap::getSnapToken($params);

            // Simpan snap token
            $pembayaran->update(['snap_token' => $snapToken]);

            // Return view dengan snap token
            return view('films.midtrans-payment', compact('pemesanan', 'snapToken'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Callback dari Midtrans (Webhook)
     */
    public function callback(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $orderId = $notification->order_id;
            $paymentType = $notification->payment_type;

            // Cari pembayaran berdasarkan transaction_id
            $pembayaran = Pembayaran::where('transaction_id', $orderId)->first();

            if (!$pembayaran) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            $pemesanan = $pembayaran->pemesanan;

            // Update berdasarkan status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $this->updateToSuccess($pembayaran, $pemesanan, $paymentType);
                }
            } elseif ($transactionStatus == 'settlement') {
                $this->updateToSuccess($pembayaran, $pemesanan, $paymentType);
            } elseif ($transactionStatus == 'pending') {
                $pembayaran->update([
                    'status_pembayaran' => 'Pending',
                    'status_verifikasi' => 'pending',
                    'payment_type' => $paymentType
                ]);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $this->updateToFailed($pembayaran, $pemesanan);
            }

            return response()->json(['message' => 'Callback processed']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Halaman finish setelah pembayaran
     */
    public function finish($pemesanan_id)
    {
        $pemesanan = Pemesanan::with('pembayaran')->findOrFail($pemesanan_id);

        // Cek kepemilikan
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        return view('films.payment-finish', compact('pemesanan'));
    }

    /**
     * Helper: Update ke status sukses
     */
    private function updateToSuccess($pembayaran, $pemesanan, $paymentType)
    {
        $pembayaran->update([
            'status_pembayaran' => 'Lunas',
            'status_verifikasi' => 'approved',
            'payment_type' => $paymentType,
            'verified_at' => now(),
            'tanggal_pembayaran' => now(),
        ]);

        $pemesanan->update([
            'status_pemesanan' => 'Lunas'
        ]);
    }

    /**
     * Helper: Update ke status gagal
     */
    private function updateToFailed($pembayaran, $pemesanan)
    {
        $pembayaran->update([
            'status_pembayaran' => 'Gagal',
            'status_verifikasi' => 'rejected',
        ]);

        $pemesanan->update([
            'status_pemesanan' => 'Dibatalkan'
        ]);
    }
}