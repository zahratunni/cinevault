@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 md:mb-14 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-3 leading-tight">Riwayat Transaksi Anda</h1>
            <p class="text-gray-500 text-lg md:text-xl max-w-2xl mx-auto">Lihat semua pemesanan tiket bioskop Anda dalam satu tempat.</p>
        </div>

        <!-- Riwayat List -->
        @if ($riwayat->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-200 p-10 md:p-16 text-center shadow-xl max-w-md mx-auto my-12">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1
                     1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-700 text-xl md:text-2xl font-semibold mb-3">Belum ada riwayat pemesanan</p>
                <p class="text-gray-500 text-base md:text-lg mb-8">Mulai petualangan sinematik Anda sekarang!</p>
                <a href="{{ route('home') }}" class="inline-block bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white 
                font-bold py-3.5 px-10 rounded-xl transition-all shadow-lg transform hover:scale-105">
                    Pesan Tiket Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 md:gap-8">
                @foreach ($riwayat as $item)
                    @php
                        $jadwal = $item->jadwal;
                        $film = $jadwal ? $jadwal->film : null;
                    @endphp

                    <div class="transaction-item bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6 md:p-7 flex flex-col gap-6">

                            <!-- Film Header dengan Poster dan Judul -->
                            <div class="flex flex-col sm:flex-row gap-5 items-center sm:items-start">
                                <!-- Poster Film -->
                                <div class="flex-shrink-0 mx-auto sm:mx-0">
                                    @if($film && $film->poster_url)
                                        <img src="{{ asset($film->poster_url) }}" 
                                             alt="{{ $film->judul }}"
                                             class="w-28 h-40 object-cover rounded-xl shadow-lg border-2 border-gray-100 transform hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-28 h-40 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl border-2 border-gray-200 
                                        text-gray-400 text-sm font-semibold shadow-md">
                                            <div class="text-center p-2">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 
                                                    002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-xs">Poster<br>Tidak Ada</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Informasi Film -->
                                <div class="flex-1 text-center sm:text-left w-full">
                                    <!-- Judul Film -->
                                    <h3 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-2 leading-tight">
                                        {{ $film ? $film->judul : '🎬 Film Tidak Ditemukan' }}
                                    </h3>

                                    <!-- Detail Jadwal -->
                                    @if($jadwal)
                                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-3 gap-y-1 text-sm text-gray-600 mb-3">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->translatedFormat('d F Y') }}
                                            </span>
                                            <span class="text-gray-300">•</span>
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} WIB
                                            </span>
                                            <span class="text-gray-300">•</span>
                                            <span class="flex items-center gap-1.5 font-semibold text-blue-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                                </svg>
                                                {{ $jadwal->studio->nama_studio }}
                                            </span>
                                        </div>
                                    @else
                                        <p class="text-gray-400 text-sm mb-3 italic flex items-center justify-center sm:justify-start gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Jadwal tidak tersedia
                                        </p>
                                    @endif

                                    <!-- Badge Status -->
                                    <div class="mt-2">
                                        @if($item->tiket_dicetak_at)
                                            <span class="inline-flex items-center gap-1.5 bg-purple-100 border border-purple-300 text-purple-700 px-3 py-1.5 rounded-full font-semibold text-xs">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 
                                                    7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Sudah Dicetak
                                            </span>
                                            <p class="text-xs text-purple-600 mt-2 flex items-center justify-center sm:justify-start gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Dicetak: {{ $item->tiket_dicetak_at->format('d/m/Y H:i') }}
                                            </p>
                                        @elseif($item->status_pemesanan === 'Lunas')
                                            <span class="inline-flex items-center gap-1.5 bg-green-100 border border-green-300 text-green-700 px-3 py-1.5 rounded-full font-semibold text-xs">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586
                                                     7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Lunas
                                            </span>
                                        @elseif($item->status_pemesanan === 'Menunggu Bayar')
                                            <span class="inline-flex items-center gap-1.5 bg-yellow-100 border border-yellow-300 text-yellow-700 px-3 py-1.5 rounded-full font-semibold text-xs animate-pulse">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Menunggu Pembayaran
                                            </span>
                                        @elseif($item->status_pemesanan === 'Kadaluarsa')
                                            <span class="inline-flex items-center gap-1.5 bg-red-100 border border-red-300 text-red-700 px-3 py-1.5 rounded-full font-semibold text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Kadaluarsa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 bg-gray-100 border border-gray-300 text-gray-700 px-3 py-1.5 rounded-full font-semibold text-xs">
                                                {{ $item->status_pemesanan }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <!-- Transaction Details -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                                <div>
                                    <p class="text-gray-500 mb-1 font-medium">Kode Booking</p>
                                    <p class="text-gray-800 font-bold text-lg tracking-wide flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                             d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ $item->kode_transaksi }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1 font-medium">Kursi</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($item->detailPemesanans as $detail)
                                            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                                                {{ $detail->kursi->kode_kursi }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1 font-medium">Total Bayar</p>
                                    <p class="text-green-600 font-extrabold text-xl">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1 font-medium">Tanggal Pemesanan</p>
                                    <p class="text-gray-800 font-semibold flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($item->tanggal_pemesanan)->format('d F Y, H:i') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Action Section -->
                            <div class="pt-5 border-t border-gray-100 mt-4">
                                @if($item->tiket_dicetak_at)
                                    <a href="{{ route('invoice.show', $item->pemesanan_id) }}" 
                                       class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700
                                        hover:to-purple-600 text-white font-bold py-3.5 px-6 rounded-lg transition-all shadow-md text-base transform hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1
                                             1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Lihat Invoice
                                    </a>
                                    <div class="mt-4 text-center text-sm text-purple-700 p-3 bg-purple-50 rounded-lg border border-purple-200">
                                        ✓ Tiket fisik sudah dicetak. Selamat menonton!
                                    </div>
                                @elseif($item->status_pemesanan === 'Lunas')
                                    <a href="{{ route('invoice.show', $item->pemesanan_id) }}" 
                                       class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700
                                        hover:to-blue-600 text-white font-bold py-3.5 px-6 rounded-lg transition-all shadow-md text-base transform hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2
                                             0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                        Lihat E-Ticket
                                    </a>
                                    <div class="mt-4 text-center text-sm text-gray-600 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        📌 Tunjukkan kode booking ini ke kasir untuk mencetak tiket Anda.
                                    </div>
                                @elseif($item->status_pemesanan === 'Menunggu Bayar')
                                    {{-- 🔥 TOMBOL LANJUTKAN PEMBAYARAN --}}
                                    <a href="{{ route('midtrans.create', $item->pemesanan_id) }}" 
                                       class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-yellow-500 to-yellow-400 hover:from-yellow-600
                                        hover:to-yellow-500 text-gray-900 font-bold py-3.5 px-6 rounded-lg transition-all shadow-md text-base transform hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 
                                            3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        Lanjutkan Pembayaran
                                    </a>
                                    <div class="mt-4 text-center text-sm text-yellow-800 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                        ⏰ Selesaikan pembayaran dalam <strong class="text-yellow-900">10 menit</strong> atau pesanan akan <strong class="text-yellow-900">kadaluarsa</strong>.
                                    </div>
                                @else
                                    <div class="w-full text-center text-gray-600 font-semibold py-3.5 px-6 rounded-lg bg-gray-100 border border-gray-200 text-base">
                                        Transaksi {{ $item->status_pemesanan }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Back Button -->
        <div class="text-center mt-16">
            <a href="{{ route('profile.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3.5 px-10 rounded-full transition-all shadow-sm transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Profil
            </a>
        </div>

    </div>
</div>
@endsection