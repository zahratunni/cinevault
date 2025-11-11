@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-12 pb-10 font-sans antialiased">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8"> {{-- Mengurangi max-width ke xl untuk fokus --}}
        
        <!-- Header Utama Invoice - Tidak ada lagi "Pembayaran Berhasil!" -->
        <div class="text-center mt-8 mb-10 md:mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-3 leading-tight">Invoice</h1> {{-- Judul diubah --}}
            <p class="text-gray-600 text-base md:text-lg max-w-md mx-auto">Informasi lengkap mengenai tiket bioskop Anda.</p> {{-- Deskripsi diubah --}}
        </div>

        <!-- Invoice Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
            
            <!-- Invoice Header with Booking Code -->
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 text-white px-6 py-5 text-center">
                <p class="text-sm uppercase tracking-wider opacity-90 mb-1">Kode Booking Anda:</p> {{-- Penjelasan Kode Booking --}}
                <p class="text-3xl md:text-4xl font-extrabold tracking-wide">{{ $pemesanan->kode_transaksi }}</p> {{-- Ukuran kode booking diperbesar --}}
            </div>

            <!-- Invoice Body -->
            <div class="p-6 md:p-8 space-y-7">
                
                <!-- Status Pemesanan -->
                <div class="text-center">
                    @if($pemesanan->status_pemesanan === 'Lunas')
                        <span class="inline-flex items-center gap-2 bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded-full font-semibold text-base">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 
                                7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Pemesanan Lunas
                        </span>
                    @elseif($pemesanan->status_pemesanan === 'Menunggu Bayar')
                        <span class="inline-flex items-center gap-2 bg-yellow-100 border border-yellow-300 text-yellow-700 px-4 py-2 rounded-full font-semibold text-base animate-pulse">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Menunggu Pembayaran
                        </span>
                    @elseif($pemesanan->status_pemesanan === 'Kadaluarsa')
                        <span class="inline-flex items-center gap-2 bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded-full font-semibold text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pemesanan Kadaluarsa
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 bg-gray-100 border border-gray-300 text-gray-700 px-4 py-2 rounded-full font-semibold text-base">
                            Status: {{ $pemesanan->status_pemesanan }}
                        </span>
                    @endif
                </div>

                <!-- Film Details Section -->
                <div>
                    <h2 class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 
                            20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                        </svg>
                        Detail Film
                    </h2>
                    <div class="flex flex-col sm:flex-row gap-4 items-start bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <img src="{{ asset($pemesanan->jadwal->film->poster_url) }}" 
                             alt="{{ $pemesanan->jadwal->film->judul }}" 
                             class="w-24 h-36 object-cover rounded-md flex-shrink-0 shadow-md">
                        <div class="flex-1 text-gray-700">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $pemesanan->jadwal->film->judul }}</h3>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10
                                         0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                    </svg>
                                    <strong>Studio:</strong> {{ $pemesanan->jadwal->studio->nama_studio }}
                                </p>
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->translatedFormat('d F Y') }}
                                </p>
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_selesai)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Seats Section -->
                <div>
                    <h2 class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Kursi Anda
                    </h2>
                    <div class="flex flex-wrap gap-2 text-gray-800">
                        @foreach($pemesanan->detailPemesanans as $detail)
                        <span class="bg-blue-100 text-blue-800 px-3.5 py-1.5 rounded-full text-sm font-semibold shadow-sm">
                            {{ $detail->kursi->kode_kursi }}
                        </span>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Payment Summary Section -->
                <div>
                    <h2 class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Rincian Pembayaran
                    </h2>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>{{ $pemesanan->detailPemesanans->count() }} x Tiket @ Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}</span>
                            <span class="font-semibold">Rp {{ number_format($pemesanan->harga_dasar_total, 0, ',', '.') }}</span>
                        </div>
                        @if($pemesanan->pembayaran)
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Metode Pembayaran</span>
                            <span class="font-medium">{{ $pemesanan->pembayaran->metode_bayar }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Waktu Pembayaran</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($pemesanan->pembayaran->tanggal_pembayaran)->format('d/m/Y H:i') }} WIB</span>
                        </div>
                        @endif
                        <hr class="border-gray-200">
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-lg font-bold text-gray-800">Total Dibayar</span>
                            <span class="text-2xl font-extrabold text-blue-700">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Info -->
            <div class="bg-blue-50 border-t border-blue-200 px-6 py-5">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1 text-gray-700">
                        <p class="font-bold text-base mb-1.5">Informasi Penting:</p>
                        <ul class="text-sm space-y-1.5 list-disc pl-5">
                            <li>Mohon tunjukkan <strong>Kode Booking</strong> ini di kasir untuk menukarkan tiket Anda.</li>
                            <li>Pastikan Anda datang ke bioskop <strong>minimal 15 menit</strong> sebelum jadwal tayang.</li>
                            <li>Invoice ini adalah bukti pembelian yang sah. Harap simpan dengan baik.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 gap-4 mt-8 mb-6">
            <a href="{{ route('profile.riwayat') }}" 
               class="inline-flex items-center justify-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3.5 px-6 rounded-xl transition-all shadow-sm transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004 12v1a8.001 8.001 0 0115.356-2m-6.356-5H20v5"></path>
                </svg>
                Lihat Semua Riwayat Pemesanan
            </a>
            @if($pemesanan->status_pemesanan === 'Menunggu Bayar')
                <a href="{{ route('payment.show', $pemesanan->pemesanan_id) }}" 
                   class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-yellow-500 to-yellow-400 hover:from-yellow-600 
                   hover:to-yellow-500 text-gray-900 font-bold py-3.5 px-6 rounded-xl transition-all shadow-md text-base transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Lanjutkan Pembayaran
                </a>
            @endif
        </div>

        <p class="text-center text-xs text-gray-500 mt-6 flex items-center justify-center gap-1.5">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 
                0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            Transaksi Anda aman dan terenkripsi.
        </p>

    </div>
</div>

<!-- Notifikasi Success -->
@if(session('success'))
<div class="fixed top-6 right-6 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg z-50 animate-bounce-custom text-base flex items-center gap-2">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 
        10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
    </svg>
    <p class="font-bold">{{ session('success') }}</p>
</div>
<script>
    const style = document.createElement('style');
    style.innerHTML = `
    @keyframes bounce-custom {
      0%, 100% {
        transform: translateY(-25%);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
      }
      50% {
        transform: translateY(0);
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
      }
    }
    .animate-bounce-custom {
        animation: bounce-custom 1s infinite;
    }
    `;
    document.head.appendChild(style);

    setTimeout(() => {
        const notification = document.querySelector('.fixed.top-6');
        if (notification) notification.remove();
    }, 4000);
</script>
@endif
@endsection