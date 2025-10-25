@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        
        <!-- Header -->
        <div class="mb-8 md:mb-12 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-2">Riwayat Transaksi</h1>
            <p class="text-gray-500 text-base md:text-lg">Semua pemesanan tiket Anda dalam satu tempat</p>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-3 mb-8 md:mb-10">
            <button onclick="filterStatus('all', event)" class="filter-btn active px-6 py-2 rounded-full font-semibold text-sm transition-all bg-[#007BFF] text-white shadow-md hover:bg-[#0056B3]">
                Semua ({{ $riwayat->count() }})
            </button>
            <button onclick="filterStatus('Lunas', event)" class="filter-btn px-6 py-2 rounded-full font-semibold text-sm transition-all bg-white text-gray-700 hover:bg-gray-100 border border-gray-200">
                Lunas ({{ $riwayat->where('status_pemesanan', 'Lunas')->count() }})
            </button>
            <button onclick="filterStatus('Menunggu Bayar', event)" class="filter-btn px-6 py-2 rounded-full font-semibold text-sm transition-all bg-white text-gray-700 hover:bg-gray-100 border border-gray-200">
                Pending ({{ $riwayat->where('status_pemesanan', 'Menunggu Bayar')->count() }})
            </button>
            <button onclick="filterStatus('Kadaluarsa', event)" class="filter-btn px-6 py-2 rounded-full font-semibold text-sm transition-all bg-white text-gray-700 hover:bg-gray-100 border border-gray-200">
                Kadaluarsa ({{ $riwayat->where('status_pemesanan', 'Kadaluarsa')->count() }})
            </button>
            <button onclick="filterStatus('Dibatalkan', event)" class="filter-btn px-6 py-2 rounded-full font-semibold text-sm transition-all bg-white text-gray-700 hover:bg-gray-100 border border-gray-200">
                Dibatalkan ({{ $riwayat->where('status_pemesanan', 'Dibatalkan')->count() }})
            </button>
        </div>

        <!-- Riwayat List -->
        @if ($riwayat->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-lg">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-600 text-lg mb-2">Belum ada riwayat pemesanan</p>
                <p class="text-gray-500 text-sm mb-6">Mulai petualangan sinematik Anda sekarang!</p>
                <a href="{{ route('home') }}" class="inline-block bg-[#007BFF] hover:bg-[#0056B3] text-white font-bold py-3 px-8 rounded-xl transition-all shadow-md">
                    Pesan Tiket Sekarang
                </a>
            </div>
        @else
            <div class="space-y-6 md:space-y-8">
                @foreach ($riwayat as $item)
                <div class="transaction-item bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all" data-status="{{ $item->status_pemesanan }}">
                    <div class="p-6 flex flex-col gap-6">
                        
                        <!-- Film Header Section (Poster, Title, Details) -->
                        <div class="flex flex-col sm:flex-row gap-5 items-center sm:items-start">
                            <div class="flex-shrink-0">
                                <img src="{{ asset($item->jadwal->film->poster_url) }}" 
                                     alt="{{ $item->jadwal->film->judul }}" 
                                     class="w-24 h-36 object-cover rounded-lg shadow-sm border border-gray-100">
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="text-xl font-bold text-gray-800 mb-1 leading-tight">{{ $item->jadwal->film->judul }}</h3>
                                <p class="text-gray-500 text-sm mb-3">
                                    {{ \Carbon\Carbon::parse($item->jadwal->tanggal_tayang)->format('d M Y') }} &bull; 
                                    {{ \Carbon\Carbon::parse($item->jadwal->jam_mulai)->format('H:i') }} WIB &bull; 
                                    {{ $item->jadwal->studio->nama_studio }}
                                </p>
                                <!-- Badge Status - positioned here for better context with film details -->
                                <div class="mt-2">
                                    @if($item->status_pemesanan === 'Lunas')
                                        <span class="inline-flex items-center gap-1 bg-green-500/10 border border-green-400 text-green-700 px-3 py-1.5 rounded-full font-semibold text-xs">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg> {{-- Checkmark icon --}}
                                            Lunas
                                        </span>
                                    @elseif($item->status_pemesanan === 'Menunggu Bayar')
                                        <span class="inline-flex items-center gap-1 bg-yellow-500/10 border border-yellow-400 text-yellow-700 px-3 py-1.5 rounded-full font-semibold text-xs animate-pulse">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{-- Clock icon --}}
                                            Pending
                                        </span>
                                    @elseif($item->status_pemesanan === 'Kadaluarsa')
                                        <span class="inline-flex items-center gap-1 bg-red-500/10 border border-red-400 text-red-700 px-3 py-1.5 rounded-full font-semibold text-xs">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{-- X-circle icon --}}
                                            Kadaluarsa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-gray-500/10 border border-gray-400 text-gray-700 px-3 py-1.5 rounded-full font-semibold text-xs">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg> {{-- X icon --}}
                                            {{ $item->status_pemesanan }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-2">

                        <!-- Transaction Details Section -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                            <div>
                                <p class="text-gray-500 mb-1">Kode Booking</p>
                                <p class="text-gray-800 font-bold text-lg tracking-wider">{{ $item->kode_transaksi }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Kursi</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($item->detailPemesanans as $detail)
                                        <span class="bg-gray-200 text-gray-800 px-2.5 py-1 rounded text-xs font-semibold">{{ $detail->kursi->kode_kursi }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Total Bayar</p>
                                <p class="text-gray-800 font-bold text-lg">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Tanggal Pemesanan</p>
                                <p class="text-gray-800 font-semibold">{{ \Carbon\Carbon::parse($item->tanggal_pemesanan)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Action Section (Buttons & Messages) -->
                        <div class="pt-4 border-t border-gray-100 mt-4">
                            @if($item->status_pemesanan === 'Lunas')
                                <a href="{{ route('invoice.show', $item->pemesanan_id) }}" 
                                   class="w-full inline-flex items-center justify-center gap-2 bg-[#007BFF] hover:bg-[#0056B3] text-white font-bold py-3.5 px-6 rounded-lg transition-all shadow-md text-base">
                                   <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25m-9-9z" /></svg> {{-- Document icon --}}
                                    Lihat E-Ticket
                                </a>
                                <div class="mt-4 text-center text-sm text-gray-600 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <span>Tunjukkan kode booking ini ke kasir untuk mencetak tiket Anda.</span>
                                </div>
                            @elseif($item->status_pemesanan === 'Menunggu Bayar')
                                <a href="{{ route('payment.show', $item->pemesanan_id) }}" 
                                   class="w-full inline-flex items-center justify-center gap-2 bg-[#FFC107] hover:bg-[#FFB300] text-gray-900 font-bold py-3.5 px-6 rounded-lg transition-all shadow-md text-base">
                                   <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9H19.5m-16.5 5.25h6m-6 2.25h3M19.5 9a2.25 2.25 0 00-2.25-2.25H12M19.5 9a2.25 2.25 0 012.25 2.25v.75m-2.25-2.25H19.5m0 0a2.25 2.25 0 00-2.25 2.25v.75m2.25-2.25v.75m0 13.5V12a2.25 2.25 0 00-2.25-2.25H12M4.5 19.5m0-13.5V12c0 .621.504 1.125 1.125 1.125h4.5M4.5 19.5V12c0 .621.504 1.125 1.125 1.125h4.5m-6.75 0h7.5" /></svg> {{-- Credit card icon --}}
                                    Lanjutkan Pembayaran
                                </a>
                                <div class="mt-4 text-center text-sm text-yellow-800 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <span>Mohon selesaikan pembayaran sebelum waktu kadaluarsa.</span>
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
        <div class="text-center mt-12">
            <a href="{{ route('profile.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-8 rounded-full transition-all shadow-sm">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg> {{-- Arrow left icon --}}
                Kembali ke Profil
            </a>
        </div>

    </div>
</div>

<!-- Filter Script -->
<script>
function filterStatus(status, event) {
    const items = document.querySelectorAll('.transaction-item');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button styles
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-[#007BFF]', 'text-white', 'shadow-md', 'hover:bg-[#0056B3]');
        btn.classList.add('bg-white', 'text-gray-700', 'hover:bg-gray-100', 'border', 'border-gray-200');
    });
    event.target.classList.remove('bg-white', 'text-gray-700', 'hover:bg-gray-100', 'border', 'border-gray-200');
    event.target.classList.add('active', 'bg-[#007BFF]', 'text-white', 'shadow-md', 'hover:bg-[#0056B3]');
    
    // Filter items
    items.forEach(item => {
        if (status === 'all') {
            item.style.display = 'block';
        } else {
            if (item.dataset.status === status) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        }
    });
}
</script>
@endsection