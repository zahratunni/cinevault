@extends('layouts.kasir')

@section('title', 'Konfirmasi Pembayaran - Kasir')
@section('page-title', 'Konfirmasi Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Konfirmasi Pembayaran</h1>
        <p class="text-gray-600 mt-1">Pilih metode pembayaran untuk menyelesaikan transaksi</p>
    </div>

    <!-- Info Pemesanan -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <!-- Kode Booking -->
        <div class="text-center mb-6 pb-6 border-b">
            <p class="text-sm text-gray-500 mb-2">Kode Booking</p>
            <p class="text-4xl font-bold text-blue-600 tracking-wider font-mono">
                {{ $pemesanan->kode_transaksi }}
            </p>
        </div>

        <!-- Total Pembayaran -->
        <div class="text-center mb-8">
            <div class="inline-block bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl p-8 shadow-xl">
                <p class="text-sm mb-2 opacity-90">Total Pembayaran</p>
                <p class="text-6xl font-bold mb-1">
                    Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                </p>
                <p class="text-sm opacity-75">
                    {{ $pemesanan->detailPemesanans->count() }} Tiket × Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Detail Pemesanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-start">
                    <span class="text-2xl mr-3">🎬</span>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Film</p>
                        <p class="font-bold text-gray-800">{{ $pemesanan->jadwal->film->judul }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-start">
                    <span class="text-2xl mr-3">🏛️</span>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Studio</p>
                        <p class="font-bold text-gray-800">{{ $pemesanan->jadwal->studio->nama_studio }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-start">
                    <span class="text-2xl mr-3">📅</span>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal & Waktu</p>
                        <p class="font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->isoFormat('D MMMM Y') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }} WIB
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-start">
                    <span class="text-2xl mr-3">🪑</span>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Kursi</p>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($pemesanan->detailPemesanans as $detail)
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-semibold">
                                    {{ $detail->kursi->kode_kursi }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilih Metode Pembayaran -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">
            Pilih Metode Pembayaran
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- TUNAI -->
            <form action="{{ route('kasir.pembayaran.confirmTunai', $pemesanan->pemesanan_id) }}" 
                  method="POST"
                  onsubmit="return confirm('✅ Konfirmasi pembayaran tunai sebesar Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}?\n\nPastikan customer sudah memberikan uang yang cukup.')">
                @csrf
                <button type="submit" 
                        class="w-full h-full group relative overflow-hidden rounded-2xl border-3 border-orange-300 hover:border-orange-500 bg-gradient-to-br from-orange-50 to-orange-100 hover:from-orange-100 hover:to-orange-200 p-8 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                    
                    <div class="text-8xl mb-4 group-hover:scale-110 transition-transform duration-300">💵</div>
                    <div class="font-bold text-2xl mb-2 text-gray-800">Tunai (Cash)</div>
                    <div class="text-sm text-gray-600 mb-4">Pembayaran tunai langsung ke kasir</div>
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg">
                        ✅ Konfirmasi Pembayaran
                    </div>
                    <div class="absolute top-4 right-4 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full">Cepat</div>
                </button>
            </form>

            <!-- MIDTRANS -->
            <a href="{{ route('kasir.pembayaran.midtrans', $pemesanan->pemesanan_id) }}" 
               class="block group relative overflow-hidden rounded-2xl border-3 border-green-300 hover:border-green-500 bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 p-8 transition-all duration-300 hover:shadow-2xl hover:scale-105 text-center">
                
                <div class="text-8xl mb-4 group-hover:scale-110 transition-transform duration-300">📱</div>
                <div class="font-bold text-2xl mb-2 text-gray-800">QRIS / E-Wallet</div>
                <div class="text-sm text-gray-600 mb-4">GoPay • ShopeePay • DANA • OVO</div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg">
                    🔗 Generate QR Code
                </div>
                <div class="absolute top-4 right-4 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">Digital</div>
            </a>
        </div>

        <!-- Info -->
        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Tips untuk Kasir:</p>
                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                        <li><strong>Tunai:</strong> Pastikan customer sudah memberikan uang yang cukup sebelum konfirmasi</li>
                        <li><strong>QRIS/E-Wallet:</strong> Tunjukkan QR code ke customer untuk di-scan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6 text-center">
        <a href="{{ route('kasir.pemesanan.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Pemesanan
        </a>
    </div>
</div>
@endsection