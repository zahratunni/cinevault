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
                <p class="text-gray-400 mb-6">Silakan selesaikan pembayaran Anda</p>
                
                <p class="text-sm text-gray-500 mb-6">Halaman ini akan otomatis refresh untuk cek status pembayaran</p>

                <a href="{{ route('payment.show', $pemesanan->pemesanan_id) }}" 
                   class="inline-block bg-gray-700 hover:bg-gray-600 text-white font-bold py-4 px-8 rounded-xl">
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