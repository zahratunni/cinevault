@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        
        <!-- Header -->
        <div class="text-center mb-8 md:mb-12">
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 md:mb-3">Pembayaran</h1>
            <p class="text-gray-400 text-sm md:text-lg">Pilih metode pembayaran dan selesaikan transaksi</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 md:gap-8">
            
            <!-- Left: Payment Form -->
            <div class="bg-[#1a1a1a] rounded-xl md:rounded-2xl shadow-2xl border border-gray-800 p-6 md:p-8">
                <h2 class="text-lg md:text-xl font-bold text-white mb-6">Metode Pembayaran</h2>
                
                <form action="{{ route('payment.process', $pemesanan->pemesanan_id) }}" method="POST" id="paymentForm">
                    @csrf
                    
                    <!-- Payment Methods -->
                    <div class="space-y-3 mb-8">
                        <label class="block">
                            <input type="radio" name="metode_bayar" value="Tunai" class="peer hidden" required>
                            <div class="p-4 border-2 border-gray-700 rounded-lg cursor-pointer peer-checked:border-[#FFA500] peer-checked:bg-[#FFA500]/10 hover:border-gray-600 transition">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-gray-400 peer-checked:text-[#FFA500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="font-semibold text-white">Tunai</span>
                                </div>
                            </div>
                        </label>

                        <label class="block">
                            <input type="radio" name="metode_bayar" value="Debit/Kredit" class="peer hidden">
                            <div class="p-4 border-2 border-gray-700 rounded-lg cursor-pointer peer-checked:border-[#FFA500] peer-checked:bg-[#FFA500]/10 hover:border-gray-600 transition">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span class="font-semibold text-white">Debit/Kredit</span>
                                </div>
                            </div>
                        </label>

                        <label class="block">
                            <input type="radio" name="metode_bayar" value="E-Wallet" class="peer hidden">
                            <div class="p-4 border-2 border-gray-700 rounded-lg cursor-pointer peer-checked:border-[#FFA500] peer-checked:bg-[#FFA500]/10 hover:border-gray-600 transition">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-semibold text-white">E-Wallet</span>
                                </div>
                            </div>
                        </label>

                        <label class="block">
                            <input type="radio" name="metode_bayar" value="Transfer Bank" class="peer hidden">
                            <div class="p-4 border-2 border-gray-700 rounded-lg cursor-pointer peer-checked:border-[#FFA500] peer-checked:bg-[#FFA500]/10 hover:border-gray-600 transition">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                    </svg>
                                    <span class="font-semibold text-white">Transfer Bank</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Amount Input -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Nominal Bayar</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold">Rp</span>
                            <input type="number" 
                                   name="nominal_dibayar" 
                                   id="nominalInput"
                                   value="{{ $pemesanan->total_bayar }}"
                                   min="{{ $pemesanan->total_bayar }}"
                                   class="w-full bg-gray-900 border border-gray-700 rounded-lg py-3 pl-12 pr-4 text-white font-semibold focus:border-[#FFA500] focus:outline-none"
                                   required>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Minimal: Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-[#FFA500] hover:bg-[#FF8C00] text-black font-bold py-4 rounded-xl transition-all shadow-lg">
                        Bayar Sekarang
                    </button>
                </form>
            </div>

            <!-- Right: Order Summary -->
            <div>
                <div class="bg-[#1a1a1a] rounded-xl md:rounded-2xl shadow-2xl border border-gray-800 p-6 md:p-8 mb-6">
                    <h2 class="text-lg md:text-xl font-bold text-white mb-6">Ringkasan Pesanan</h2>
                    
                    <!-- Film Info -->
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

                    <!-- Booking Code -->
                    <div class="mb-6 pb-6 border-b border-gray-800">
                        <p class="text-xs text-gray-500 mb-1">Kode Pemesanan</p>
                        <p class="text-lg font-bold text-[#FFA500]">{{ $pemesanan->kode_transaksi }}</p>
                    </div>

                    <!-- Seats -->
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

                    <!-- Price Details -->
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

                <!-- Warning -->
                <div class="bg-yellow-900/20 border border-yellow-600/40 rounded-xl p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs text-yellow-200/90">Pastikan data pembayaran sudah benar sebelum melanjutkan</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif
@endsection