@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen">

    <!-- Main Content Section -->
    <div class="relative bg-gradient-to-b from-black via-gray-950 to-black py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-16 items-start">

                <!-- Left: Poster -->
                <div class="lg:col-span-2">
                    <div class="sticky top-28">
                        <img src="{{ asset($film->poster_url) }}"
                             alt="{{ $film->judul }}"
                             class="w-full rounded-2xl shadow-[0_0_25px_rgba(254,169,35,0.4)] border border-gray-800">
                    </div>
                </div>

                <!-- Right: Film Details -->
                <div class="lg:col-span-3 space-y-10">
                    
                    <!-- Title & Rating -->
                    <div>
                        <div class="flex items-start justify-between gap-4 mb-6">
                            <h1 class="text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                                {{ $film->judul }}
                            </h1>
                            <span class="bg-[#FEA923] text-black px-5 py-2.5 rounded-xl font-bold text-xl shadow-md">
                                {{ $film->rating }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="bg-[#FEA923] text-black px-5 py-2 rounded-lg font-semibold shadow-sm">
                                {{ $film->genre }}
                            </span>
                            <span class="bg-gray-800 text-white px-5 py-2 rounded-lg font-semibold">
                                {{ $film->durasi_menit }} min
                            </span>
                        </div>
                    </div>

                    <!-- Trailer Button -->
                    @if($film->trailer_url)
                    <div>
                        <a href="{{ $film->trailer_url }}" target="_blank"
                           class="inline-flex items-center gap-3 bg-[#FEA923] hover:bg-[#ffb937] text-black font-bold px-10 py-4 rounded-full transition-all duration-300 shadow-[0_0_15px_rgba(254,169,35,0.4)] hover:scale-105">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                            </svg>
                            Watch Trailer
                        </a>
                    </div>
                    @endif

                    <!-- Sinopsis -->
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-5 border-l-4 border-[#FEA923] pl-4">
                            Sinopsis
                        </h2>
                        <p class="text-gray-300 leading-relaxed text-lg">
                            {{ $film->sinopsis }}
                        </p>
                    </div>

                    <!-- Film Information -->
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-6 border-l-4 border-[#FEA923] pl-4">
                            Film Information
                        </h2>
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-gray-500 text-sm font-semibold mb-1">PRODUSER</p>
                                    <p class="text-white text-lg">{{ $film->produser ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-semibold mb-1">SUTRADARA</p>
                                    <p class="text-white text-lg">{{ $film->sutradara ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-gray-500 text-sm font-semibold mb-1">PENULIS</p>
                                    <p class="text-white text-lg">{{ $film->penulis ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-semibold mb-1">PRODUKSI</p>
                                    <p class="text-white text-lg">{{ $film->produksi ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="pt-2">
                                <p class="text-gray-500 text-sm font-semibold mb-2">CAST</p>
                                <p class="text-white leading-relaxed text-lg">{{ $film->cast_list ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Tayang Section -->
    <div class="bg-gradient-to-b from-gray-950 to-black border-t border-gray-800 py-20 mt-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <h2 class="text-4xl font-extrabold text-white mb-10 text-center">
                Jadwal Tayang
            </h2>

            @if($jadwalsByDate->isNotEmpty())
                <!-- Date Tabs -->
                <div class="flex flex-wrap justify-center gap-4 mb-12">
                    @foreach($jadwalsByDate as $tanggal => $jadwals)
                        @php
                            $date = \Carbon\Carbon::parse($tanggal);
                            $isToday = $date->isToday();
                            $isTomorrow = $date->isTomorrow();
                            $isWeekend = $date->isWeekend();
                        @endphp
                        <button 
                            onclick="showJadwal('{{ $tanggal }}')"
                            data-date="{{ $tanggal }}"
                            class="jadwal-date-btn w-48 bg-gray-900 hover:bg-[#FEA923] border border-gray-800 hover:border-[#FEA923] rounded-xl p-5 transition-all duration-300 text-center group shadow hover:shadow-[0_0_15px_rgba(254,169,35,0.4)]">
                            <div class="text-sm text-gray-400 group-hover:text-black mb-1">
                                @if($isToday)
                                    Hari Ini
                                @elseif($isTomorrow)
                                    Besok
                                @else
                                    {{ $date->format('l') }}
                                @endif
                            </div>
                            <div class="text-xl font-bold text-white group-hover:text-black">
                                {{ $date->format('d M Y') }}
                            </div>
                            @if($isWeekend)
                                <div class="text-sm text-[#FEA923] group-hover:text-black mt-1 font-medium">Weekend</div>
                            @endif
                            <div class="text-xs text-gray-500 group-hover:text-black mt-2">
                                {{ $jadwals->count() }} jadwal
                            </div>
                        </button>
                    @endforeach
                </div>

                <!-- Jadwal Content per Date -->
                @foreach($jadwalsByDate as $tanggal => $jadwals)
                    <div class="jadwal-content hidden" data-date="{{ $tanggal }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($jadwals as $jadwal)
                                <a href="{{ route('booking.kursi', $jadwal->jadwal_id) }}" 
                                   class="block bg-gray-900 hover:bg-[#FEA923] border border-gray-800 hover:border-[#FEA923] rounded-xl p-6 transition-all duration-300 group hover:scale-[1.03] shadow hover:shadow-[0_0_15px_rgba(254,169,35,0.3)]">
                                    <div class="mb-4">
                                        <span class="text-4xl font-extrabold text-white group-hover:text-black">
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-400 group-hover:text-black mb-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        {{ $jadwal->studio->nama_studio }}
                                    </div>
                                    <div class="text-xl font-bold text-[#FEA923] group-hover:text-black">
                                        Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }}
                                    </div>
                                    <div class="mt-4 text-center text-sm font-semibold text-gray-500 group-hover:text-black">
                                        Pilih Kursi →
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-gray-900 rounded-2xl p-16 text-center shadow-inner border border-gray-800">
                    <div class="text-6xl mb-4">📅</div>
                    <h3 class="text-2xl font-bold text-white mb-3">Tidak Ada Jadwal</h3>
                    <p class="text-gray-400 text-lg">
                        Film ini belum memiliki jadwal tayang. Silakan cek kembali nanti.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateButtons = document.querySelectorAll('.jadwal-date-btn');
        const jadwalContents = document.querySelectorAll('.jadwal-content');
        
        window.showJadwal = function(tanggal) {
            jadwalContents.forEach(content => content.classList.add('hidden'));
            dateButtons.forEach(btn => {
                btn.classList.remove('bg-[#FEA923]', 'text-black', 'border-[#FEA923]');
                btn.classList.add('bg-gray-900', 'text-white', 'border-gray-800');
            });
            const selectedContent = document.querySelector(`.jadwal-content[data-date="${tanggal}"]`);
            if (selectedContent) selectedContent.classList.remove('hidden');
            const selectedButton = document.querySelector(`.jadwal-date-btn[data-date="${tanggal}"]`);
            if (selectedButton) {
                selectedButton.classList.remove('bg-gray-900', 'text-white', 'border-gray-800');
                selectedButton.classList.add('bg-[#FEA923]', 'text-black', 'border-[#FEA923]');
            }
        };

        if (dateButtons.length > 0) {
            const firstDate = dateButtons[0].getAttribute('data-date');
            showJadwal(firstDate);
        }
    });
</script>
@endsection
