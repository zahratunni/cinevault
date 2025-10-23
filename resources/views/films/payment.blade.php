@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        
        <!-- Header -->
        <div class="text-center mb-8 md:mb-12">
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 md:mb-3">Pembayaran</h1>
            <p class="text-gray-400 text-sm md:text-lg">Selesaikan pembayaran dengan Midtrans</p>
        </div>

        @if(session('error'))
        <div class="bg-red-900/20 border border-red-500 rounded-xl p-4 mb-6">
            <p class="text-red-400 text-sm">{{ session('error') }}</p>
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6 md:gap-8">
            
            <!-- Left: Payment Button -->
            <div class="bg-[#1a1a1a] rounded-xl md:rounded-2xl shadow-2xl border border-gray-800 p-6 md:p-8">
                <h2 class="text-lg md:text-xl font-bold text-white mb-6">Metode Pembayaran</h2>
                
                <!-- 🔥 TOMBOL MIDTRANS -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-1 mb-6">
                    <div class="bg-[#1a1a1a] rounded-lg p-6">
                        <div class="mb-4">
                            <h3 class="text-white font-bold text-lg mb-2">Pembayaran Aman & Terpercaya</h3>
                            <p class="text-gray-300 text-sm">Bayar dengan berbagai metode pilihan Anda</p>
                        </div>
                        
                        <!-- Logo Payment Methods -->
                        <div class="flex flex-wrap gap-2 mb-6">
                            <img src="{{ asset('payment/gopay.jpg') }}" class="h-10 rounded" alt="GoPay">
                            <img src="{{ asset('payment/shopeepay.png') }}" class="h-10 rounded" alt="ShopeePay">
                            <img src="{{ asset('payment/dana.jpg') }}" class="h-10 rounded" alt="DANA">
                            <img src="{{ asset('payment/ovo.png') }}" class="h-10 rounded" alt="OVO">
                            <img src="{{ asset('payment/bca.jpg') }}" class="h-10 rounded" alt="BCA">
                            <img src="{{ asset('payment/bni.png') }}" class="h-10 rounded" alt="BNI">
                            <img src="{{ asset('payment/mandiri.png') }}" class="h-10 rounded" alt="Mandiri">
                            <span class="text-gray-400 text-sm self-center">+10 metode lainnya</span>
                        </div>

                        <a href="{{ route('midtrans.create', $pemesanan->pemesanan_id) }}" 
                           class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg text-center mb-4">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Bayar Sekarang
                        </a>
                        
                        <div class="space-y-2">
                            <p class="text-green-400 text-xs text-center font-semibold">
                                ✓ Langsung Lunas | ✓ E-ticket Otomatis | ✓ Aman & Terenkripsi
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="bg-blue-900/20 border border-blue-600/40 rounded-xl p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-blue-400 font-bold text-sm mb-1">Keunggulan Pembayaran:</p>
                            <ul class="text-xs text-blue-200/90 space-y-1">
                                <li>✓ 15+ metode pembayaran tersedia</li>
                                <li>✓ Verifikasi otomatis & real-time</li>
                                <li>✓ E-ticket langsung terbit setelah bayar</li>
                                <li>✓ Transaksi aman dengan enkripsi SSL</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Ringkasan Pesanan -->
            <div>
                <div class="bg-[#1a1a1a] rounded-xl md:rounded-2xl shadow-2xl border border-gray-800 p-6 md:p-8 mb-6">
                    <h2 class="text-lg md:text-xl font-bold text-white mb-6">Ringkasan Pesanan</h2>
                    <div class="flex gap-4 mb-6 pb-6 border-b border-gray-800">
                        <img src="{{ asset($pemesanan->jadwal->film->poster_url) }}" 
                             alt="{{ $pemesanan->jadwal->film->judul }}" 
                             class="w-20 h-28 object-cover rounded-lg border border-gray-700">
                        <div>
                            <h3 class="font-bold text-white mb-2">{{ $pemesanan->jadwal->film->judul }}</h3>
                            <p class="text-sm text-gray-400">{{ $pemesanan->jadwal->studio->nama_studio }}</p>
                            <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d/m/Y') }} | {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }}</p>
                        </div>
                    </div>
                    <div class="mb-6 pb-6 border-b border-gray-800">
                        <p class="text-xs text-gray-500 mb-1">Kode Pemesanan</p>
                        <p class="text-lg font-bold text-[#FFA500]">{{ $pemesanan->kode_transaksi }}</p>
                    </div>
                    <div class="mb-6 pb-6 border-b border-gray-800">
                        <p class="text-xs text-gray-500 mb-2">Kursi</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($pemesanan->detailPemesanans as $detail)
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-sm font-semibold">
                                {{ $detail->kursi->kode_kursi }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">{{ $pemesanan->detailPemesanans->count() }} x Tiket</span>
                            <span class="text-white font-semibold">Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-gray-800">
                            <span class="font-bold text-white">Total</span>
                            <span class="font-bold text-[#FFA500] text-xl">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Peringatan -->
                <div class="bg-yellow-900/20 border border-yellow-600/40 rounded-xl p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="text-yellow-400 font-bold text-sm mb-1">Perhatian!</p>
                            <p class="text-xs text-yellow-200/90">Selesaikan pembayaran dalam waktu yang ditentukan untuk menghindari pembatalan otomatis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection