@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen pt-12 pb-8 font-sans antialiased"> {{-- Mengurangi padding atas dan bawah --}}
    <div class="max-w-2xl mx-auto px-3 sm:px-4 lg:px-6"> {{-- Mengurangi max-width menjadi 2xl --}}
        
        <!-- Success Header -->
        <div class="text-center mt-6 mb-7"> {{-- Mengurangi margin --}}
            <div class="inline-flex items-center justify-center w-12 h-12 bg-[#007BFF] rounded-full mb-2 shadow-md"> {{-- Ukuran ikon sukses sedikit diperkecil --}}
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-[#2C3E50] mb-1">Pembayaran Berhasil!</h1> {{-- Ukuran font H1 diperkecil --}}
            <p class="text-gray-600 text-sm">Tiket Anda telah dikonfirmasi. Selamat menikmati film.</p> {{-- Ukuran font P diperkecil --}}
        </div>

        <!-- Invoice Card - Lebih Compact -->
        <div class="bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden mb-6"> {{-- Mengurangi margin bawah --}}
            
            <!-- Header dengan Kode Booking -->
            <div class="bg-gradient-to-r from-[#0056B3] to-[#007BFF] text-white px-5 py-4 text-center"> {{-- Mengurangi padding --}}
                <p class="text-xs uppercase tracking-wider opacity-90 mb-1">Invoice Pembelian Tiket</p> {{-- Ukuran font diperkecil --}}
                <p class="text-xl font-extrabold tracking-wide mb-2">{{ $pemesanan->kode_transaksi }}</p> {{-- Ukuran font diperkecil --}}
                <div class="inline-block bg-white/20 px-3 py-1 rounded-full"> {{-- Mengurangi padding --}}
                    <p class="text-white text-xs font-semibold">✓ {{ $pemesanan->status_pemesanan }}</p> {{-- Ukuran font diperkecil --}}
                </div>
            </div>

            <!-- Body Invoice -->
            <div class="p-5 space-y-6"> {{-- Mengurangi padding dan space-y --}}
                
                <!-- Film Details Section -->
                <div>
                    <h2 class="text-xs font-bold text-[#007BFF] uppercase tracking-wider mb-3 flex items-center gap-2"> {{-- Ukuran font heading diperkecil --}}
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                        </svg>
                        Detail Film
                    </h2>
                    <div class="flex gap-3 items-start border border-gray-200 rounded-lg p-3 bg-gray-50"> {{-- Mengurangi padding dan gap --}}
                        <img src="{{ asset($pemesanan->jadwal->film->poster_url) }}" 
                             alt="{{ $pemesanan->jadwal->film->judul }}" 
                             class="w-20 h-32 object-cover rounded-md flex-shrink-0 shadow-sm"> {{-- Ukuran poster diperkecil --}}
                        <div class="flex-1 text-gray-700">
                            <h3 class="text-lg font-bold text-[#2C3E50] mb-1">{{ $pemesanan->jadwal->film->judul }}</h3> {{-- Ukuran font H3 diperkecil --}}
                            <div class="space-y-0.5 text-xs"> {{-- Ukuran font detail diperkecil dan space-y dikurangi --}}
                                <p class="flex items-center gap-1.5"><i class="fas fa-desktop text-[#FFC107] text-sm"></i> <strong>Studio:</strong> {{ $pemesanan->jadwal->studio->nama_studio }}</p>
                                <p class="flex items-center gap-1.5"><i class="fas fa-calendar-alt text-[#FFC107] text-sm"></i> <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d F Y') }}</p>
                                <p class="flex items-center gap-1.5"><i class="fas fa-clock text-[#FFC107] text-sm"></i> <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_selesai)->format('H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Seats Section -->
                <div>
                    <h2 class="text-xs font-bold text-[#007BFF] uppercase tracking-wider mb-3 flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Kursi Anda
                    </h2>
                    <div class="flex flex-wrap gap-1.5 text-gray-800"> {{-- Mengurangi gap --}}
                        @foreach($pemesanan->detailPemesanans as $detail)
                        <span class="bg-[#FFC107] text-[#2C3E50] px-3 py-1.5 rounded-md text-xs font-semibold shadow-sm"> {{-- Mengurangi padding dan ukuran font --}}
                            {{ $detail->kursi->kode_kursi }}
                        </span>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Payment Summary Section -->
                <div>
                    <h2 class="text-xs font-bold text-[#007BFF] uppercase tracking-wider mb-3 flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Rincian Pembayaran
                    </h2>
                    <div class="bg-gray-50 rounded-lg p-3 space-y-2 border border-gray-200"> {{-- Mengurangi padding dan space-y --}}
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>{{ $pemesanan->detailPemesanans->count() }} x Tiket @ Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}</span>
                            <span class="font-semibold">Rp {{ number_format($pemesanan->harga_dasar_total, 0, ',', '.') }}</span>
                        </div>
                        @if($pemesanan->pembayaran)
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Metode Pembayaran</span>
                            <span>{{ $pemesanan->pembayaran->metode_bayar }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Waktu Pembayaran</span>
                            <span>{{ \Carbon\Carbon::parse($pemesanan->pembayaran->tanggal_pembayaran)->format('d/m/Y H:i') }} WIB</span>
                        </div>
                        @endif
                        <hr class="border-gray-200">
                        <div class="flex justify-between items-center pt-1.5"> {{-- Mengurangi padding top --}}
                            <span class="text-base font-bold text-[#2C3E50]">Total Dibayar</span> {{-- Ukuran font total dibayar diperkecil --}}
                            <span class="text-xl font-extrabold text-[#007BFF]">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span> {{-- Ukuran font total dibayar diperkecil --}}
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Info -->
            <div class="bg-[#EBF7FF] border-t border-[#007BFF]/30 px-5 py-4"> {{-- Mengurangi padding --}}
                <div class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-[#007BFF] flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"> {{-- Ukuran ikon diperkecil --}}
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1 text-gray-700">
                        <p class="font-bold text-sm mb-1.5">Penting:</p> {{-- Ukuran font diperkecil --}}
                        <ul class="text-xs space-y-1">
                            <li>• Mohon tukarkan tiket Anda di kasir dengan menunjukkan <strong>Kode Booking</strong> ini.</li>
                            <li>• Datang <strong>15 menit</strong> sebelum jadwal tayang.</li>
                            <li>• Simpan invoice ini sebagai bukti pembelian yang sah.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons - Hanya tombol Riwayat Pemesanan -->
        <div class="grid grid-cols-1 gap-4 mb-4"> {{-- Hanya satu kolom --}}
            <a href="{{ route('profile.riwayat') }}" class="bg-gray-200 hover:bg-gray-300 text-[#2C3E50] font-semibold py-3 rounded-lg transition-all text-center flex items-center justify-center gap-2">
                <i class="fas fa-history"></i>
                Riwayat Pemesanan
            </a>
            {{-- Tombol Kembali ke Beranda dihilangkan --}}
        </div>

        <p class="text-center text-xs text-gray-500 mt-4"> {{-- Mengurangi margin top --}}
            <svg class="w-3 h-3 inline-block mr-1 text-[#007BFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            Transaksi Anda aman & terenkripsi
        </p>

    </div>
</div>

<!-- Notifikasi Success -->
@if(session('success'))
<div class="fixed top-16 right-4 bg-[#007BFF] text-white px-4 py-2.5 rounded-lg shadow-lg z-50 animate-bounce text-sm"> {{-- Mengurangi padding dan top --}}
    <p class="font-bold">✓ {{ session('success') }}</p>
</div>
<script>
    setTimeout(() => {
        const notification = document.querySelector('.fixed.top-16');
        if (notification) notification.remove();
    }, 3000);
</script>
@endif
@endsection