
@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-2xl mx-auto px-4 md:px-6 text-center">
        
        @if($pemesanan->pembayaran && $pemesanan->pembayaran->status_pembayaran === 'Lunas')
            <!-- Success -->
            <div class="bg-[#1a1a1a] rounded-2xl border border-green-500 p-8">
                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-3">Pembayaran Berhasil!</h1>
                <p class="text-gray-400 mb-6">Transaksi Anda telah dikonfirmasi</p>
                
                <div class="bg-gray-900 rounded-xl p-6 mb-6">
                    <p class="text-gray-400 text-sm mb-2">Kode Pemesanan</p>
                    <p class="text-2xl font-bold text-[#FFA500]">{{ $pemesanan->kode_transaksi }}</p>
                </div>

                <a href="{{ route('invoice.show', $pemesanan->pemesanan_id) }}" 
                   class="inline-block bg-[#FFA500] hover:bg-[#FF8C00] text-black font-bold py-4 px-8 rounded-xl">
                    Lihat E-Ticket Saya
                </a>
            </div>
        @else
    <!-- Pending/Waiting -->
    <div class="bg-[#1a1a1a] rounded-2xl border border-yellow-500 p-8">
        <div class="w-20 h-20 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
            <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-white mb-3">Menunggu Pembayaran</h1>
        <p class="text-gray-400 mb-6">Silakan selesaikan pembayaran Anda di Midtrans</p>
        
        <div class="bg-gray-900 rounded-xl p-6 mb-6">
            <p class="text-gray-400 text-sm mb-2">Kode Pemesanan</p>
            <p class="text-2xl font-bold text-[#FFA500]">{{ $pemesanan->kode_transaksi }}</p>
        </div>

        <!-- Button Konfirmasi Pembayaran -->
        <div class="mb-6 p-6 bg-gradient-to-r from-blue-900/40 to-purple-900/40 border border-blue-500 rounded-xl">
            <div class="flex items-start space-x-3 mb-4">
                <svg class="w-6 h-6 text-blue-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-left">
                    <p class="text-white font-semibold mb-1">Sudah Bayar?</p>
                    <p class="text-gray-400 text-sm">Jika pembayaran Anda di Midtrans sudah berhasil, klik tombol di bawah untuk konfirmasi dan mendapatkan e-ticket Anda.</p>
                </div>
            </div>
            <a href="{{ route('midtrans.confirm', $pemesanan->pemesanan_id) }}" 
               class="block w-full bg-gradient-to-r from-[#FFA500] to-[#FF8C00] hover:from-[#FF8C00] hover:to-[#FFA500] text-black font-bold py-4 px-6 rounded-xl text-center transition-all duration-300 transform hover:scale-105">
                ✅ Konfirmasi Pembayaran Berhasil
            </a>
        </div>

        <p class="text-sm text-gray-500 mb-6">Atau tunggu, halaman ini akan otomatis refresh setiap 3 detik</p>

        <a href="{{ route('payment.show', $pemesanan->pemesanan_id) }}" 
           class="inline-block bg-gray-700 hover:bg-gray-600 text-white font-bold py-4 px-8 rounded-xl transition-colors">
            Kembali ke Pembayaran
        </a>
    </div>
@endif
    </div>
</div>

<script>
// Auto refresh setiap 3 detik untuk cek status
@if($pemesanan->pembayaran && $pemesanan->pembayaran->status_pembayaran !== 'Lunas')
    setTimeout(() => {
        location.reload();
    }, 3000);
@endif
</script>
@endsection
