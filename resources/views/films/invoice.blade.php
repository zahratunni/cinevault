@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        
        <!-- Success Header -->
        <div class="text-center mb-8 md:mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-green-500 rounded-full mb-4 md:mb-5">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 md:mb-3">Pembayaran Berhasil</h1>
            <p class="text-gray-400 text-sm md:text-lg">Tiket Anda telah dikonfirmasi. Selamat menonton!</p>
        </div>

        <!-- Invoice Card -->
        <div class="bg-[#1a1a1a] rounded-xl md:rounded-2xl shadow-2xl border border-gray-800 overflow-hidden mb-6 md:mb-8">
            
            <!-- Header -->
            <div class="bg-green-600 text-white px-6 py-5 md:px-8 md:py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs uppercase tracking-widest mb-2 font-bold">Invoice</p>
                        <p class="text-xl md:text-3xl font-black tracking-wider">{{ $pemesanan->kode_transaksi }}</p>
                    </div>
                    <div class="bg-white px-4 py-2 rounded-lg">
                        <p class="text-green-600 font-black text-sm">{{ $pemesanan->status_pemesanan }}</p>
                    </div>
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
                    <h2 class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 md:mb-5">Kursi</h2>
                    <div class="flex flex-wrap gap-2 md:gap-3">
                        @foreach($pemesanan->detailPemesanans as $detail)
                        <span class="bg-[#FFA500] text-black px-4 py-2 md:px-5 md:py-3 rounded-lg text-sm md:text-base font-bold">
                            {{ $detail->kursi->kode_kursi }}
                        </span>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-800">

                <!-- Payment Info -->
                @if($pemesanan->pembayaran)
                <div>
                    <h2 class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 md:mb-5">Informasi Pembayaran</h2>
                    <div class="bg-gray-900 rounded-lg p-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Metode Pembayaran</span>
                            <span class="text-white font-semibold">{{ $pemesanan->pembayaran->metode_bayar }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tanggal Pembayaran</span>
                            <span class="text-white font-semibold">{{ \Carbon\Carbon::parse($pemesanan->pembayaran->tanggal_pembayaran)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Nominal Dibayar</span>
                            <span class="text-white font-semibold">Rp {{ number_format($pemesanan->pembayaran->nominal_dibayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Status</span>
                            <span class="text-green-500 font-bold">{{ $pemesanan->pembayaran->status_pembayaran }}</span>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-800">
                @endif

                <!-- Payment Summary -->
                <div>
                    <h2 class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 md:mb-5">Rincian Pembayaran</h2>
                    <div class="space-y-3 md:space-y-4 text-sm md:text-base">
                        <div class="flex justify-between">
                            <span class="text-gray-400">{{ $pemesanan->detailPemesanans->count() }} x Tiket</span>
                            <span class="text-white font-semibold">Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-3 md:pt-4 border-t border-gray-800">
                            <span class="text-lg md:text-xl font-bold text-white">Total Dibayar</span>
                            <span class="text-xl md:text-2xl font-black text-green-500">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-800">

                <!-- QR Code Placeholder -->
                <div>
                    <h2 class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 md:mb-5">QR Code Check-in</h2>
                    <div class="bg-white p-6 rounded-lg text-center">
                        <div class="w-48 h-48 mx-auto bg-gray-200 rounded-lg flex items-center justify-center">
                            <p class="text-gray-600 font-semibold">QR Code<br>{{ $pemesanan->kode_transaksi }}</p>
                        </div>
                        <p class="text-xs text-gray-600 mt-4">Tunjukkan QR Code ini saat check-in</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-4 rounded-xl transition-all">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Tiket
            </button>
            <a href="{{ route('home') }}" class="bg-[#FFA500] hover:bg-[#FF8C00] text-black font-bold py-4 rounded-xl transition-all text-center">
                Kembali ke Beranda
            </a>
        </div>

        <p class="text-center text-xs md:text-sm text-gray-500">Simpan invoice ini sebagai bukti pembelian tiket</p>

    </div>
</div>

@if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif
@endsection