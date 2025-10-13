@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-black via-[#0d0d0d] to-black min-h-screen pt-24 md:pt-32 pb-20 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success Indicator -->
        <div class="text-center mb-10 md:mb-14">
            <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-green-500/90 rounded-full mb-4 md:mb-5 shadow-lg shadow-green-500/40">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-wide text-white mb-2">Pemesanan Berhasil</h1>
            <p class="text-gray-400 text-sm md:text-lg font-medium">Silakan selesaikan pembayaran untuk konfirmasi tiket Anda</p>
        </div>

        <!-- Receipt Card -->
        <div class="bg-[#121212]/90 rounded-2xl shadow-2xl border border-gray-800 overflow-hidden backdrop-blur-sm mb-8">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#FEA923] to-[#ff8800] text-black px-6 py-5 md:px-8 md:py-6">
                <div class="text-center">
                    <p class="text-xs uppercase tracking-[0.25em] mb-2 font-bold">Kode Pemesanan</p>
                    <p class="text-2xl md:text-3xl font-black tracking-wider break-all">{{ $pemesanan->kode_transaksi }}</p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6 md:p-8 space-y-8">

                <!-- Film Details -->
                <div>
                    <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Detail Film</h2>
                    <div class="flex flex-col sm:flex-row gap-5">
                        <img src="{{ asset($pemesanan->jadwal->film->poster_url) }}" 
                             alt="{{ $pemesanan->jadwal->film->judul }}" 
                             class="w-28 h-40 object-cover rounded-xl border border-gray-700 shadow-md mx-auto sm:mx-0">
                        <div class="flex-1 space-y-3">
                            <h3 class="text-xl font-bold text-white">{{ $pemesanan->jadwal->film->judul }}</h3>
                            <div class="space-y-2 text-sm md:text-base">
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-gray-500 sm:w-28 font-medium">Studio</span>
                                    <span class="text-white font-semibold">{{ $pemesanan->jadwal->studio->nama_studio }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-gray-500 sm:w-28 font-medium">Tanggal</span>
                                    <span class="text-white font-semibold">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d F Y') }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-gray-500 sm:w-28 font-medium">Waktu</span>
                                    <span class="text-white font-semibold">{{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_selesai)->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-800">

                <!-- Seats -->
                <div>
                    <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Kursi Terpilih</h2>
                    <div class="flex flex-wrap justify-center sm:justify-start gap-3">
                        @foreach($pemesanan->detailPemesanans as $detail)
                        <span class="bg-[#FEA923] text-black px-4 py-2 md:px-5 md:py-3 rounded-xl font-bold shadow-md">
                            {{ $detail->kursi->kode_kursi }}
                        </span>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-800">

                <!-- Payment Details -->
                <div>
                    <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Rincian Pembayaran</h2>
                    <div class="space-y-4 text-sm md:text-base">
                        <div class="flex justify-between">
                            <span class="text-gray-400">{{ $pemesanan->detailPemesanans->count() }} x Tiket</span>
                            <span class="text-white font-semibold">Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-gray-800">
                            <span class="text-lg font-bold text-white">Total</span>
                            <span class="text-2xl font-extrabold text-[#FEA923]">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-800">

                <!-- Status -->
                <div class="bg-yellow-900/20 border border-yellow-600/40 rounded-xl p-5 flex items-start gap-4">
                    <svg class="w-6 h-6 text-yellow-500 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-base font-bold text-yellow-500 mb-1">Status: {{ $pemesanan->status_pemesanan }}</p>
                        <p class="text-sm text-yellow-300/90">Selesaikan pembayaran dalam 10 menit atau pesanan akan dibatalkan otomatis.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action -->
        <a href="{{ route('payment.show', $pemesanan->pemesanan_id) }}" 
           class="w-full block text-center bg-[#FEA923] hover:bg-[#ff8c00] text-black font-bold text-lg py-4 rounded-xl transition-all duration-200 shadow-lg hover:scale-[1.02]">
           Lanjutkan Pembayaran
        </a>

        <p class="text-center text-xs md:text-sm text-gray-500 mt-6">Simpan kode pemesanan untuk referensi pengambilan tiket</p>
    </div>
</div>
@endsection
