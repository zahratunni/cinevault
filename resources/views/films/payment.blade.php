@extends('layouts.app')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen pt-24 md:pt-32 pb-20"> {{-- Updated main background --}}
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        
        <!-- Header -->
        <div class="text-center mb-8 md:mb-12">
            <h1 class="text-2xl md:text-4xl font-extrabold text-[#2C3E50] mb-2 md:mb-3">Pembayaran</h1> {{-- Updated text color and font weight --}}
            <p class="text-gray-600 text-sm md:text-lg">Selesaikan pembayaran dengan Midtrans</p> {{-- Updated text color --}}
        </div>

        @if(session('error'))
        <div class="bg-red-50 border border-red-300 rounded-xl p-4 mb-6"> {{-- Updated error message style --}}
            <p class="text-red-700 text-sm">{{ session('error') }}</p>
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6 md:gap-8">
            
            <!-- Left: Payment Button -->
            <div class="bg-white rounded-xl md:rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8"> {{-- Updated card background, shadow, and border --}}
                <h2 class="text-lg md:text-xl font-bold text-[#2C3E50] mb-6">Metode Pembayaran</h2> {{-- Updated text color --}}
                
                <!-- 🔥 TOMBOL MIDTRANS -->
                <div class="bg-gradient-to-r from-[#007BFF] to-[#0056B3] rounded-xl p-1 mb-6"> {{-- Updated gradient to primary blue --}}
                    <div class="bg-white rounded-lg p-6"> {{-- Updated inner background --}}
                        <div class="mb-4">
                            <h3 class="text-[#2C3E50] font-bold text-lg mb-2">Pembayaran Aman & Terpercaya</h3> {{-- Updated text color --}}
                            <p class="text-gray-700 text-sm">Bayar dengan berbagai metode pilihan Anda</p> {{-- Updated text color --}}
                        </div>
                        
                        <!-- Logo Payment Methods -->
                        <div class="flex flex-wrap gap-2 mb-6">
                            <img src="{{ asset('payment/gopay.jpg') }}" class="h-10 rounded shadow-sm" alt="GoPay">
                            <img src="{{ asset('payment/shopeepay.png') }}" class="h-10 rounded shadow-sm" alt="ShopeePay">
                            <img src="{{ asset('payment/dana.jpg') }}" class="h-10 rounded shadow-sm" alt="DANA">
                            <img src="{{ asset('payment/ovo.png') }}" class="h-10 rounded shadow-sm" alt="OVO">
                            <img src="{{ asset('payment/bca.jpg') }}" class="h-10 rounded shadow-sm" alt="BCA">
                            <img src="{{ asset('payment/bni.png') }}" class="h-10 rounded shadow-sm" alt="BNI">
                            <img src="{{ asset('payment/mandiri.png') }}" class="h-10 rounded shadow-sm" alt="Mandiri">
                            <span class="text-gray-500 text-sm self-center">+10 metode lainnya</span> {{-- Updated text color --}}
                        </div>

                        <a href="{{ route('midtrans.create', $pemesanan->pemesanan_id) }}" 
                           class="block w-full bg-gradient-to-r from-[#007BFF] to-[#0056B3] hover:opacity-90 text-white font-bold py-4 rounded-xl transition-all shadow-lg text-center mb-4"> {{-- Updated button gradient and hover --}}
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Bayar Sekarang
                        </a>
                        
                        <div class="space-y-2">
                            <p class="text-green-600 text-xs text-center font-semibold"> {{-- Updated text color --}}
                                ✓ Langsung Lunas | ✓ E-ticket Otomatis | ✓ Aman & Terenkripsi
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="bg-[#EBF7FF] border border-[#CCE5FF] rounded-xl p-4"> {{-- Updated info box style --}}
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-[#007BFF] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"> {{-- Updated icon color --}}
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-[#007BFF] font-bold text-sm mb-1">Keunggulan Pembayaran:</p> {{-- Updated text color --}}
                            <ul class="text-sm text-blue-700 space-y-1"> {{-- Updated text color and size --}}
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
                <div class="bg-white rounded-xl md:rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8 mb-6"> {{-- Updated card background, shadow, and border --}}
                    <h2 class="text-lg md:text-xl font-bold text-[#2C3E50] mb-6">Ringkasan Pesanan</h2> {{-- Updated text color --}}
                    <div class="flex gap-4 mb-6 pb-6 border-b border-gray-200"> {{-- Updated border color --}}
                        <img src="{{ asset($pemesanan->jadwal->film->poster_url) }}" 
                             alt="{{ $pemesanan->jadwal->film->judul }}" 
                             class="w-20 h-28 object-cover rounded-lg border border-gray-200 shadow-sm"> {{-- Updated border and added shadow --}}
                        <div>
                            <h3 class="font-bold text-[#2C3E50] mb-2">{{ $pemesanan->jadwal->film->judul }}</h3> {{-- Updated text color --}}
                            <p class="text-sm text-gray-600">{{ $pemesanan->jadwal->studio->nama_studio }}</p> {{-- Updated text color --}}
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d/m/Y') }} | {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }}</p> {{-- Updated text color --}}
                        </div>
                    </div>
                    <div class="mb-6 pb-6 border-b border-gray-200"> {{-- Updated border color --}}
                        <p class="text-xs text-gray-500 mb-1">Kode Pemesanan</p> {{-- Updated text color --}}
                        <p class="text-lg font-bold text-[#007BFF]">{{ $pemesanan->kode_transaksi }}</p> {{-- Updated text color to primary blue --}}
                    </div>
                    <div class="mb-6 pb-6 border-b border-gray-200"> {{-- Updated border color --}}
                        <p class="text-xs text-gray-500 mb-2">Kursi</p> {{-- Updated text color --}}
                        <div class="flex flex-wrap gap-2">
                            @foreach($pemesanan->detailPemesanans as $detail)
                            <span class="bg-[#E3F2FD] text-[#007BFF] px-3 py-1 rounded-full text-sm font-semibold"> {{-- Updated badge style to match home --}}
                                {{ $detail->kursi->kode_kursi }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $pemesanan->detailPemesanans->count() }} x Tiket</span> {{-- Updated text color --}}
                            <span class="text-[#2C3E50] font-semibold">Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}</span> {{-- Updated text color --}}
                        </div>
                        <div class="flex justify-between pt-3 border-t border-gray-200"> {{-- Updated border color --}}
                            <span class="font-bold text-[#2C3E50]">Total</span> {{-- Updated text color --}}
                            <span class="font-bold text-[#007BFF] text-xl">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span> {{-- Updated text color to primary blue --}}
                        </div>
                    </div>
                </div>
                
                <!-- Peringatan -->
                <div class="bg-[#FFFDE7] border border-[#FFC107] rounded-xl p-4"> {{-- Updated warning box style --}}
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-[#FFC107] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"> {{-- Updated icon color --}}
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="text-[#FFC107] font-bold text-sm mb-1">Perhatian!</p> {{-- Updated text color --}}
                            <p class="text-sm text-yellow-700">Selesaikan pembayaran dalam waktu yang ditentukan untuk menghindari pembatalan otomatis.</p> {{-- Updated text color and size --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection