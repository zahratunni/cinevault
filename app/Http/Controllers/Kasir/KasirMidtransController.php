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
                'phone' => '08123456789',
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

            \Log::info('✅ Snap Token Created', [
                'transaction_id' => $transaction_id,
                'pemesanan_id' => $pemesanan_id,
                'snap_token' => $snapToken
            ]);

            return view('kasir.pembayaran.qris', compact('pemesanan', 'snapToken'));
        } catch (\Exception $e) {
            \Log::error('❌ Midtrans Create Transaction Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Gagal membuat transaksi Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * Callback otomatis Midtrans
     */
    public function callback(Request $request)
    {
        \Log::info('🔔 ========== MIDTRANS CALLBACK START ==========');
        \Log::info('📥 Request Method: ' . $request->method());
        \Log::info('📦 Request Data:', $request->all());
        \Log::info('📋 Headers:', $request->headers->all());

        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status ?? null;

            \Log::info('🎯 Notification Details:', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus
            ]);

            // Cari pembayaran berdasarkan transaction_id
            $pembayaran = Pembayaran::where('transaction_id', $orderId)->first();

            if (!$pembayaran) {
                \Log::error('❌ Pembayaran tidak ditemukan', ['order_id' => $orderId]);
                return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
            }

            \Log::info('💰 Pembayaran ditemukan:', [
                'pembayaran_id' => $pembayaran->pembayaran_id,
                'pemesanan_id' => $pembayaran->pemesanan_id,
                'status_sekarang' => $pembayaran->status_pembayaran
            ]);

            $pemesanan = $pembayaran->pemesanan;

            // Handle berdasarkan status transaksi
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                \Log::info('✅ Processing SUCCESS status');
                
                // Cek fraud status untuk capture
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $this->updateToSuccess($pembayaran, $pemesanan, $paymentType);
                    } else {
                        \Log::warning('⚠️ Capture with fraud status: ' . $fraudStatus);
                    }
                } else {
                    $this->updateToSuccess($pembayaran, $pemesanan, $paymentType);
                }
                
            } elseif ($transactionStatus === 'pending') {
                \Log::info('⏳ Processing PENDING status');
                $pembayaran->update([
                    'status_pembayaran' => 'Pending',
                    'status_verifikasi' => 'pending',
                    'payment_type' => $paymentType
                ]);
                
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                \Log::info('❌ Processing FAILED status: ' . $transactionStatus);
                $this->updateToFailed($pembayaran, $pemesanan);
            }

            \Log::info('✅ ========== CALLBACK PROCESSED SUCCESSFULLY ==========');
            return response()->json(['message' => 'Callback processed successfully']);
            
        } catch (\Exception $e) {
            \Log::error('💥 ========== CALLBACK ERROR ==========');
            \Log::error('Error Message: ' . $e->getMessage());
            \Log::error('Error File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Stack Trace:', ['trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'message' => 'Callback error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman finish setelah bayar sukses
     */
    public function finish($pemesanan_id)
    {
        $pemesanan = Pemesanan::with('pembayaran')->findOrFail($pemesanan_id);

        \Log::info('🏁 Finish Page Accessed', [
            'pemesanan_id' => $pemesanan_id,
            'status_pemesanan' => $pemesanan->status_pemesanan,
            'status_pembayaran' => $pemesanan->pembayaran->status_pembayaran ?? 'no payment'
        ]);

        return redirect()->route('kasir.tiket.show', $pemesanan_id)
            ->with('success', 'Pembayaran berhasil! Tiket siap dicetak.');
    }

    /**
     * Helper sukses
     */
    private function updateToSuccess($pembayaran, $pemesanan, $paymentType)
    {
        \Log::info('💚 Updating to SUCCESS');
        
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

        \Log::info('✅ Status updated to SUCCESS', [
            'pembayaran_id' => $pembayaran->pembayaran_id,
            'pemesanan_id' => $pemesanan->pemesanan_id
        ]);
    }

    /**
     * Helper gagal
     */
    private function updateToFailed($pembayaran, $pemesanan)
    {
        \Log::info('🔴 Updating to FAILED');
        
        $pembayaran->update([
            'status_pembayaran' => 'Gagal',
            'status_verifikasi' => 'rejected',
        ]);

        $pemesanan->update([
            'status_pemesanan' => 'Dibatalkan'
        ]);

        \Log::info('❌ Status updated to FAILED', [
            'pembayaran_id' => $pembayaran->pembayaran_id,
            'pemesanan_id' => $pemesanan->pemesanan_id
        ]);
    }

    /**
     * ⭐ MANUAL CALLBACK - untuk testing di localhost
     * Gunakan setelah customer selesai bayar di simulator Midtrans
     */
    public function manualCallback($pemesanan_id)
    {
        try {
            $pemesanan = Pemesanan::with('pembayaran')->findOrFail($pemesanan_id);
            $pembayaran = $pemesanan->pembayaran;

            if (!$pembayaran || !$pembayaran->transaction_id) {
                return redirect()->route('kasir.kelola.pemesanan')
                    ->with('error', 'Data pembayaran tidak ditemukan');
            }

            \Log::info('🔍 Manual Callback Started', [
                'pemesanan_id' => $pemesanan_id,
                'transaction_id' => $pembayaran->transaction_id
            ]);

            // Cek status langsung dari Midtrans API
            $status = \Midtrans\Transaction::status($pembayaran->transaction_id);

            \Log::info('📊 Midtrans Status Response:', [
                'order_id' => $status->order_id,
                'transaction_status' => $status->transaction_status,
                'payment_type' => $status->payment_type ?? 'unknown',
                'gross_amount' => $status->gross_amount ?? 0
            ]);

            $transactionStatus = $status->transaction_status;
            $paymentType = $status->payment_type ?? 'unknown';

            // Update berdasarkan status
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                // PEMBAYARAN SUKSES ✅
                $pembayaran->update([
                    'status_pembayaran' => 'Lunas',
                    'status_verifikasi' => 'approved',
                    'payment_type' => $paymentType,
                    'verified_at' => now(),
                    'tanggal_pembayaran' => now(),
                ]);

                $pemesanan->update(['status_pemesanan' => 'Lunas']);

                \Log::info('✅ Manual Callback SUCCESS', [
                    'pemesanan_id' => $pemesanan_id,
                    'status' => 'Lunas'
                ]);

                return redirect()->route('kasir.tiket.show', $pemesanan_id)
                    ->with('success', '✅ Pembayaran berhasil dikonfirmasi! Silakan cetak tiket.');

            } elseif ($transactionStatus === 'pending') {
                // MASIH PENDING ⏳
                \Log::info('⏳ Payment still pending', ['pemesanan_id' => $pemesanan_id]);

                return redirect()->route('kasir.kelola.detail', $pemesanan_id)
                    ->with('info', '⏳ Pembayaran masih pending. Tunggu customer menyelesaikan pembayaran, lalu cek ulang.');

            } else {
                // GAGAL / EXPIRE / CANCEL ❌
                $pembayaran->update([
                    'status_pembayaran' => 'Gagal',
                    'status_verifikasi' => 'rejected',
                ]);

                $pemesanan->update(['status_pemesanan' => 'Dibatalkan']);

                \Log::warning('❌ Payment failed', [
                    'pemesanan_id' => $pemesanan_id,
                    'status' => $transactionStatus
                ]);

                return redirect()->route('kasir.kelola.pemesanan')
                    ->with('error', '❌ Pembayaran gagal atau dibatalkan. Status: ' . $transactionStatus);
            }

        } catch (\Exception $e) {
            \Log::error('💥 Manual Callback Error', [
                'pemesanan_id' => $pemesanan_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('kasir.kelola.pemesanan')
                ->with('error', '❌ Error cek status: ' . $e->getMessage());
        }
    }
}