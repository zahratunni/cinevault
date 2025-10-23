@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        
        <!-- Header -->
        <div class="text-center mb-8 md:mb-12">
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 md:mb-3">Pembayaran</h1>
            <p class="text-gray-400 text-sm md:text-lg">Pilih metode pembayaran dan selesaikan transaksi</p>
        </div>

        @if(session('error'))
        <div class="bg-red-900/20 border border-red-500 rounded-xl p-4 mb-6">
            <p class="text-red-400 text-sm">{{ session('error') }}</p>
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6 md:gap-8">
            
            <!-- Left: Payment Method Selection -->
            <div class="bg-[#1a1a1a] rounded-xl md:rounded-2xl shadow-2xl border border-gray-800 p-6 md:p-8">
                <h2 class="text-lg md:text-xl font-bold text-white mb-6">Metode Pembayaran</h2>
                
                <!-- 🔥 TOMBOL MIDTRANS (RECOMMENDED) -->
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-1">
                        <div class="bg-[#1a1a1a] rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-white font-bold text-lg mb-1">Bayar Sekarang (Instant)</h3>
                                    <p class="text-gray-300 text-sm">Otomatis langsung lunas, semua metode tersedia</p>
                                </div>
                                <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">RECOMMENDED</span>
                            </div>
                            
                            <!-- Logo Payment Methods -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <img src="{{ asset('payment/gopay.jpg') }}" class="h-8 rounded" alt="GoPay">
                                <img src="{{ asset('payment/shopeepay.png') }}" class="h-8 rounded" alt="ShopeePay">
                                <img src="{{ asset('payment/dana.jpg') }}" class="h-8 rounded" alt="DANA">
                                <img src="{{ asset('payment/ovo.png') }}" class="h-8 rounded" alt="OVO">
                                <img src="{{ asset('payment/bca.jpg') }}" class="h-8 rounded" alt="BCA">
                                <img src="{{ asset('payment/mandiri.png') }}" class="h-8 rounded" alt="Mandiri">
                                <span class="text-gray-400 text-xs self-center">+10 metode lainnya</span>
                            </div>

                            <a href="{{ route('midtrans.create', $pemesanan->pemesanan_id) }}" 
                               class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg text-center">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Bayar dengan Midtrans
                            </a>
                            <p class="text-green-400 text-xs text-center mt-3 font-semibold">
                                ✓ Langsung Lunas | ✓ Aman & Terpercaya | ✓ Support 24/7
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-[#1a1a1a] text-gray-500">atau</span>
                    </div>
                </div>

                <!-- 📤 UPLOAD BUKTI TRANSFER MANUAL (BACKUP) -->
                <div class="bg-gray-900/50 border border-gray-700 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-white font-bold">Upload Bukti Transfer (Manual)</h3>
                        <span class="bg-yellow-600 text-white text-xs font-bold px-3 py-1 rounded-full">BACKUP</span>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">Jika Midtrans bermasalah, Anda bisa transfer manual dan upload bukti. <span class="text-yellow-400 font-semibold">Butuh verifikasi admin (1-10 menit)</span></p>
                    
                    <form action="{{ route('payment.process', $pemesanan->pemesanan_id) }}" method="POST" id="manualForm">
                        @csrf
                        
                        <!-- Custom Dropdown -->
                        <div class="mb-4">
                            <label class="block text-white font-semibold mb-3 text-sm">Pilih Metode Transfer</label>

                            <div id="dropdownTrigger" 
                                 class="flex justify-between items-center bg-gray-800 border border-gray-600 rounded-lg p-3 cursor-pointer hover:border-gray-500 transition">
                                <span id="selectedMethod" class="text-gray-400 text-sm">-- Pilih Bank/E-Wallet --</span>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <div id="dropdownMenu" class="hidden mt-2 bg-gray-800 border border-gray-600 rounded-lg overflow-hidden shadow-lg">
                                <div class="p-2">
                                    <p class="text-gray-400 text-xs px-2 mt-1 mb-1">E-Wallet</p>
                                    @foreach (['DANA'=>'dana.jpg','OVO'=>'ovo.png','GoPay'=>'gopay.jpg','ShopeePay'=>'shopeepay.png'] as $name => $img)
                                        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-700 cursor-pointer transition select-option" 
                                             data-value="{{ $name }}" data-img="{{ asset('payment/'.$img) }}">
                                            <img src="{{ asset('payment/'.$img) }}" alt="{{ $name }}" class="w-8 h-8 rounded object-contain">
                                            <span class="text-white font-semibold text-sm">{{ $name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <hr class="border-gray-700">
                                <div class="p-2">
                                    <p class="text-gray-400 text-xs px-2 mt-1 mb-1">Transfer Bank</p>
                                    @foreach (['BCA'=>'bca.jpg','BRI'=>'logo-bri-biru.png','BNI'=>'bni.png','Mandiri'=>'mandiri.png'] as $name => $img)
                                        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-700 cursor-pointer transition select-option" 
                                             data-value="{{ $name }}" data-img="{{ asset('payment/'.$img) }}">
                                            <img src="{{ asset('payment/'.$img) }}" alt="{{ $name }}" class="w-8 h-8 rounded object-contain">
                                            <span class="text-white font-semibold text-sm">{{ $name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" required>
                        </div>

                        <button type="submit" id="btnSubmit" disabled 
                            class="w-full bg-gray-700 text-gray-400 font-bold py-3 rounded-lg transition-all cursor-not-allowed text-sm">
                            Lanjut ke Upload Bukti
                        </button>
                    </form>
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
                
                <!-- Info Box -->
                <div class="bg-blue-900/20 border border-blue-600/40 rounded-xl p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-blue-400 font-bold text-sm mb-1">💡 Rekomendasi:</p>
                            <p class="text-xs text-blue-200/90">Gunakan <strong>Bayar dengan Midtrans</strong> untuk pengalaman terbaik. Pembayaran langsung terverifikasi otomatis dan Anda langsung dapat e-ticket!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const trigger = document.getElementById('dropdownTrigger');
const menu = document.getElementById('dropdownMenu');
const hiddenInput = document.getElementById('metode_pembayaran');
const selectedText = document.getElementById('selectedMethod');
const btnSubmit = document.getElementById('btnSubmit');

trigger.addEventListener('click', () => {
    menu.classList.toggle('hidden');
});

document.addEventListener('click', (e) => {
    if (!trigger.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
    }
});

document.querySelectorAll('.select-option').forEach(opt => {
    opt.addEventListener('click', () => {
        const value = opt.getAttribute('data-value');
        const imgSrc = opt.getAttribute('data-img');
        
        hiddenInput.value = value;
        selectedText.innerHTML = `<img src="${imgSrc}" class='inline-block w-6 h-6 mr-2 rounded object-contain'><span class="font-semibold text-sm">${value}</span>`;
        
        btnSubmit.disabled = false;
        btnSubmit.classList.remove('bg-gray-700', 'text-gray-400', 'cursor-not-allowed');
        btnSubmit.classList.add('bg-[#FFA500]', 'hover:bg-[#FF8C00]', 'text-black');
        
        menu.classList.add('hidden');
    });
});
</script>
@endsection