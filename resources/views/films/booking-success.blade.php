@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        
        <!-- Success Indicator -->
        <div class="text-center mb-8 md:mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-green-500 rounded-full mb-4 md:mb-5">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 md:mb-3">Pemesanan Berhasil</h1>
            <p class="text-gray-400 text-sm md:text-lg">Silakan selesaikan pembayaran untuk konfirmasi tiket</p>
        </div>

        <!-- Receipt Card -->
        <div class="bg-[#1a1a1a] rounded-xl md:rounded-2xl shadow-2xl border border-gray-800 overflow-hidden mb-6 md:mb-8">
            
            <!-- Header -->
            <div class="bg-[#FFA500] text-black px-6 py-5 md:px-8 md:py-6">
                <div class="text-center">
                    <p class="text-xs uppercase tracking-widest mb-2 md:mb-3 font-bold">Kode Pemesanan</p>
                    <p class="text-xl md:text-3xl font-black tracking-wider break-all">{{ $pemesanan->kode_transaksi }}</p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6 md:p-8 space-y-6 md:space-y-8">
                
                <!-- Film Details -->
                <div>
                    <h2 class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 md:mb-5">Detail Film</h2>
                    <div class="flex flex-col sm:flex-row gap-4 md:gap-6">
                        <img src="{{ asset($pemesanan->jadwal->film->poster_url) }}" 
                             alt="{{ $pemesanan->jadwal->film->judul }}" 
                             class="w-24 h-32 md:w-28 md:h-40 object-cover rounded-lg border border-gray-700 mx-auto sm:mx-0">
                        <div class="flex-1">
                            <h3 class="text-lg md:text-xl font-bold text-white mb-3 md:mb-5">{{ $pemesanan->jadwal->film->judul }}</h3>
                            <div class="space-y-2 md:space-y-3 text-sm md:text-base">
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-gray-500 sm:w-24 font-medium mb-1 sm:mb-0">Studio</span>
                                    <span class="text-white font-semibold">{{ $pemesanan->jadwal->studio->nama_studio }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-gray-500 sm:w-24 font-medium mb-1 sm:mb-0">Tanggal</span>
                                    <span class="text-white font-semibold">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d F Y') }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-gray-500 sm:w-24 font-medium mb-1 sm:mb-0">Waktu</span>
                                    <span class="text-white font-semibold">{{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_selesai)->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-800">

                <!-- Seats -->
                <div>
                    <h2 class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 md:mb-5">Kursi Terpilih</h2>
                    <div class="flex flex-wrap gap-2 md:gap-3">
                        @foreach($pemesanan->detailPemesanans as $detail)
                        <span class="bg-[#FFA500] text-black px-4 py-2 md:px-5 md:py-3 rounded-lg text-sm md:text-base font-bold">
                            {{ $detail->kursi->kode_kursi }}
                        </span>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-800">

                <!-- Payment Details -->
                <div>
                    <h2 class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 md:mb-5">Rincian Pembayaran</h2>
                    <div class="space-y-3 md:space-y-4 text-sm md:text-base">
                        <div class="flex justify-between">
                            <span class="text-gray-400">{{ $pemesanan->detailPemesanans->count() }} x Tiket</span>
                            <span class="text-white font-semibold">Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-3 md:pt-4 border-t border-gray-800">
                            <span class="text-lg md:text-xl font-bold text-white">Total</span>
                            <span class="text-xl md:text-2xl font-black text-[#FFA500]">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-800">

                <!-- Status -->
                <div class="bg-yellow-900/20 border border-yellow-600/40 rounded-lg md:rounded-xl p-4 md:p-5">
                    <div class="flex gap-3 md:gap-4">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm md:text-base font-bold text-yellow-500 mb-1">Status: {{ $pemesanan->status_pemesanan }}</p>
                            <p class="text-xs md:text-sm text-yellow-200/90">Selesaikan pembayaran dalam 10 menit atau pesanan akan dibatalkan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action -->
        <a href="{{ route('payment.show', $pemesanan->pemesanan_id) }}" class="w-full block text-center bg-[#FFA500] hover:bg-[#FF8C00] text-black font-bold text-base md:text-lg py-4 md:py-5 px-6 rounded-xl transition-all shadow-lg mb-6">
    Lanjutkan Pembayaran
        </a>

        <p class="text-center text-xs md:text-sm text-gray-500 mb-20">Simpan kode pemesanan untuk referensi pengambilan tiket</p>

    </div>
</div>
@endsection