<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class KasirMidtransController extends Controller
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
     * Kasir membuat transaksi Midtrans (QRIS / E-Wallet)
     */
    public function createTransaction($pemesanan_id)
    {
        $pemesanan = Pemesanan::with(['jadwal.film', 'jadwal.studio', 'detailPemesanans.kursi'])
            ->findOrFail($pemesanan_id);

        // Buat transaction_id unik
        $transaction_id = 'KASIR-' . $pemesanan->pemesanan_id . '-' . time();

        // Buat atau update pembayaran
        $pembayaran = $pemesanan->pembayaran;

        if (!$pembayaran) {
            $pembayaran = Pembayaran::create([
                'pemesanan_id' => $pemesanan->pemesanan_id,
                'user_id' => auth()->id(), // kasir yang sedang login
                'metode_bayar' => 'Online (Kasir)',
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

        // Data Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $transaction_id,
                'gross_amount' => (int) $pemesanan->total_bayar,
            ],
            'customer_details' => [
                'first_name' => 'Customer Offline',
                'email' => 'offline@kasir.local',
                'phone' => '',
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
                'gopay', 'shopeepay', 'other_qris', 'bca_va', 'bni_va', 'bri_va'
            ],
            'callbacks' => [
                'finish' => route('kasir.midtrans.finish', $pemesanan_id)
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $pembayaran->update(['snap_token' => $snapToken]);

            return view('kasir.pembayaran.qris', compact('pemesanan', 'snapToken'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat transaksi Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * Callback otomatis Midtrans
     */
   public function callback(Request $request)
{
    // ⭐ TAMBAHKAN LOGGING INI untuk debug
    \Log::info('🔔 Midtrans Callback Kasir Received', [
        'method' => $request->method(),
        'all_data' => $request->all(),
        'raw_body' => $request->getContent()
    ]);

    try {
        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $paymentType = $notification->payment_type;

        \Log::info('📦 Notification Details', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'payment_type' => $paymentType
        ]);

        $pembayaran = Pembayaran::where('transaction_id', $orderId)->first();

        if (!$pembayaran) {
            \Log::error('❌ Pembayaran tidak ditemukan', ['order_id' => $orderId]);
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        $pemesanan = $pembayaran->pemesanan;

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            $this->updateToSuccess($pembayaran, $pemesanan, $paymentType);
            \Log::info('✅ Pembayaran berhasil', ['order_id' => $orderId]);
        } elseif ($transactionStatus === 'pending') {
            $pembayaran->update([
                'status_pembayaran' => 'Pending',
                'status_verifikasi' => 'pending',
                'payment_type' => $paymentType
            ]);
            \Log::info('⏳ Pembayaran pending', ['order_id' => $orderId]);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $this->updateToFailed($pembayaran, $pemesanan);
            \Log::info('⛔ Pembayaran gagal', ['order_id' => $orderId, 'status' => $transactionStatus]);
        }

        return response()->json(['message' => 'Callback processed successfully']);
    } catch (\Exception $e) {
        \Log::error('❌ Callback Error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['message' => $e->getMessage()], 500);
    }
}
    /**
     * Halaman finish setelah bayar sukses
     */
    public function finish($pemesanan_id)
    {
        $pemesanan = Pemesanan::with('pembayaran')->findOrFail($pemesanan_id);

        return redirect()->route('kasir.tiket.show', $pemesanan_id)
            ->with('success', 'Pembayaran berhasil! Tiket siap dicetak.');
    }

    /**
     * Helper sukses
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
     * Helper gagal
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
