@extends('layouts.kasir')

@section('title', 'Konfirmasi Pembayaran - Kasir')
@section('page-title', 'Konfirmasi Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900">Konfirmasi Pembayaran</h1>
        {{-- <p class="text-lg text-gray-600">Lanjutkan untuk menyelesaikan transaksi Anda.</p> --}} 
    </div>

    <!-- Info Pemesanan -->
    <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-8 border border-gray-100">
        <!-- Kode Booking -->
        <div class="text-center mb-6 pb-6 border-b border-gray-200">
            <p class="text-sm text-gray-500 mb-2 font-medium">Kode Booking</p>
            <p class="text-4xl font-extrabold text-blue-700 tracking-wide font-mono select-all">
                {{ $pemesanan->kode_transaksi }}
            </p>
        </div>

        <!-- Total Pembayaran - Revisi Elegan -->
        <div class="text-center mb-8">
            <div class="p-6 sm:p-8 border border-green-300 rounded-xl bg-green-50 shadow-sm">
                <p class="text-base text-gray-700 mb-2 font-medium">Total Pembayaran</p>
                <p class="text-5xl font-extrabold text-green-700 mb-2 leading-tight">
                    Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $pemesanan->detailPemesanans->count() }} Tiket &times; Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}
                </p>
            </div>
        </div>
        <!-- End Total Pembayaran - Revisi Elegan -->

        <!-- Detail Pemesanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6">
            <div class="bg-gray-50 rounded-lg p-4 flex items-center shadow-sm">
                <i class="fas fa-film text-2xl text-blue-500 mr-4"></i>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Film</p>
                    <p class="font-semibold text-gray-800 text-lg">{{ $pemesanan->jadwal->film->judul }}</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 flex items-center shadow-sm">
                <i class="fas fa-desktop text-2xl text-purple-500 mr-4"></i>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Studio</p>
                    <p class="font-semibold text-gray-800 text-lg">{{ $pemesanan->jadwal->studio->nama_studio }}</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 flex items-center shadow-sm">
                <i class="fas fa-calendar-alt text-2xl text-green-500 mr-4"></i>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Tanggal & Waktu</p>
                    <p class="font-semibold text-gray-800 text-lg">
                        {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->isoFormat('D MMMM Y') }}
                    </p>
                    <p class="text-sm text-gray-600 mt-0.5">
                        Pukul {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }} WIB
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 flex items-start shadow-sm">
                <i class="fas fa-chair text-2xl text-red-500 mr-4 mt-1"></i>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Kursi</p>
                    <div class="flex flex-wrap gap-2 mt-1">
                        @foreach($pemesanan->detailPemesanans as $detail)
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $detail->kursi->kode_kursi }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilih Metode Pembayaran -->
    <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 border border-gray-100">
        <h2 class="text-3xl font-bold mb-8 text-center text-gray-900">
            Pilih Metode Pembayaran
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            <!-- TUNAI -->
            <form action="{{ route('kasir.pembayaran.confirmTunai', $pemesanan->pemesanan_id) }}" 
                  method="POST"
                  onsubmit="return confirm('✅ Konfirmasi pembayaran tunai sebesar Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}?\n\nPastikan customer sudah memberikan uang yang cukup.')">
                @csrf
                <button type="submit" 
                        class="w-full flex flex-col items-center justify-center p-6 rounded-lg border-2 border-orange-300 bg-white
                               hover:border-orange-500 hover:shadow-md transform hover:-translate-y-1 transition-all duration-200 ease-in-out group">
                    
                    <div class="text-6xl mb-3 text-orange-500 group-hover:scale-105 transition-transform duration-200">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="font-bold text-xl mb-1 text-gray-800">Tunai (Cash)</div>
                    <div class="text-sm text-gray-600 mb-4 text-center">Pembayaran langsung ke kasir</div>
                    <div class="bg-orange-500 text-white font-semibold py-2 px-6 rounded-md shadow-sm
                                group-hover:bg-orange-600 transition-colors duration-200">
                        Konfirmasi Pembayaran
                    </div>
                </button>
            </form>

            <!-- MIDTRANS -->
            <a href="{{ route('kasir.pembayaran.midtrans', $pemesanan->pemesanan_id) }}" 
               class="w-full flex flex-col items-center justify-center p-6 rounded-lg border-2 border-green-300 bg-white
                      hover:border-green-500 hover:shadow-md transform hover:-translate-y-1 transition-all duration-200 ease-in-out group text-center">
                
                <div class="text-6xl mb-3 text-green-500 group-hover:scale-105 transition-transform duration-200">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div class="font-bold text-xl mb-1 text-gray-800">QRIS / E-Wallet</div>
                <div class="text-sm text-gray-600 mb-4 text-center">GoPay • ShopeePay • DANA • OVO</div>
                <div class="bg-green-500 text-white font-semibold py-2 px-6 rounded-md shadow-sm
                            group-hover:bg-green-600 transition-colors duration-200">
                    Generate QR Code
                </div>
            </a>
        </div>

        <!-- Info -->
        <div class="mt-8 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-md">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 text-xl mt-1 mr-3"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-bold mb-1">Informasi Penting:</p>
                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                        <li><strong>Pembayaran Tunai:</strong> Harap pastikan jumlah uang yang diberikan oleh customer sudah sesuai sebelum mengkonfirmasi.</li>
                        <li><strong>Pembayaran QRIS/E-Wallet:</strong> Setelah menekan 'Generate QR Code', tunjukkan kode QR kepada customer untuk dipindai.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8 text-center">
        <a href="{{ route('kasir.pemesanan.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-900 font-semibold text-base py-2 px-5 rounded-lg 
                  bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pemesanan
        </a>
    </div>
</div>
@endsection