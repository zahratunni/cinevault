@extends('layouts.app')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen pb-20"> {{-- Added padding-bottom for overall spacing --}}

    <!-- Main Content Section: Film Details -->
    <div class="relative bg-white py-16 lg:py-24 shadow-sm border-b border-gray-100"> {{-- Adjusted padding, added subtle shadow and bottom border --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16 items-start"> {{-- Changed to 3 columns on large screens --}}

                <!-- Left: Poster -->
                <div class="lg:col-span-1 flex justify-center"> {{-- Centered poster for smaller screens --}}
                    <div class="sticky top-28 w-full max-w-sm lg:max-w-none"> {{-- Constrained width for poster on smaller screens --}}
                        <img src="{{ asset($film->poster_url) }}"
                             alt="{{ $film->judul }}"
                             class="w-full aspect-[2/3] object-cover rounded-xl shadow-[0_0_25px_rgba(0,123,255,0.2)] border border-gray-200 transform hover:scale-[1.01] transition-transform duration-300"> {{-- Added aspect ratio and hover effect --}}
                    </div>
                </div>

                <!-- Right: Film Details -->
                <div class="lg:col-span-2 space-y-8"> {{-- Spacing between sections --}}
                    
                    <!-- Title & Rating & Genres -->
                    <div>
                        <div class="flex items-center justify-between gap-4 mb-4"> {{-- Adjusted spacing --}}
                            <h1 class="text-4xl lg:text-5xl font-extrabold text-[#2C3E50] tracking-tight leading-tight">
                                {{ $film->judul }}
                            </h1>
                            <span class="bg-[#007BFF] text-white px-4 py-1.5 rounded-lg font-bold text-lg shadow-sm"> {{-- Slightly smaller rating pill --}}
                                {{ $film->rating }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3 flex-wrap text-sm"> {{-- Smaller text for tags --}}
                            <span class="bg-[#E3F2FD] text-[#007BFF] px-4 py-1.5 rounded-full font-semibold">{{ $film->genre }}</span> {{-- Rounded full for a softer look --}}
                            <span class="bg-gray-200 text-gray-700 px-4 py-1.5 rounded-full font-semibold">{{ $film->durasi_menit }} min</span>
                        </div>
                    </div>

                    <!-- Trailer Button -->
                    @if($film->trailer_url)
                    <div class="pt-2"> {{-- Added padding top --}}
                        <a href="{{ $film->trailer_url }}" target="_blank"
                           class="inline-flex items-center gap-3 bg-[#007BFF] hover:bg-[#0056B3] text-white font-bold px-8 py-3.5 rounded-full transition-all duration-300 shadow-[0_0_15px_rgba(0,123,255,0.4)] hover:scale-105">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"> {{-- Slightly smaller icon --}}
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                            </svg>
                            Watch Trailer
                        </a>
                    </div>
                    @endif

                    <!-- Sinopsis -->
                    <div class="pt-4 border-t border-gray-100"> {{-- Added top border as a subtle separator --}}
                        <h2 class="text-2xl font-bold text-[#2C3E50] mb-4 border-l-4 border-[#007BFF] pl-4">
                            Sinopsis
                        </h2>
                        <p class="text-gray-700 leading-relaxed text-base lg:text-lg"> {{-- Adjusted text size --}}
                            {{ $film->sinopsis }}
                        </p>
                    </div>

                    <!-- Film Information -->
                    <div class="pt-4 border-t border-gray-100"> {{-- Added top border as a subtle separator --}}
                        <h2 class="text-2xl font-bold text-[#2C3E50] mb-5 border-l-4 border-[#007BFF] pl-4">
                            Film Information
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6"> {{-- More responsive grid --}}
                            <div>
                                <p class="text-gray-500 text-sm font-semibold mb-1">PRODUSER</p>
                                <p class="text-[#2C3E50] text-base">{{ $film->produser ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm font-semibold mb-1">SUTRADARA</p>
                                <p class="text-[#2C3E50] text-base">{{ $film->sutradara ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm font-semibold mb-1">PENULIS</p>
                                <p class="text-[#2C3E50] text-base">{{ $film->penulis ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm font-semibold mb-1">PRODUKSI</p>
                                <p class="text-[#2C3E50] text-base">{{ $film->produksi ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-gray-100"> {{-- Separated Cast with a top border --}}
                            <p class="text-gray-500 text-sm font-semibold mb-2">CAST</p>
                            <p class="text-[#2C3E50] leading-relaxed text-base">{{ $film->cast_list ?? '-' }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Tayang Section -->
    <div class="bg-[#F9FAFB] py-16 lg:py-24"> {{-- Adjusted padding --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-[#2C3E50] mb-10 text-center relative pb-3"> {{-- Adjusted title size, added bottom border effect --}}
                <span class="relative z-10">Jadwal Tayang</span>
                <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-24 h-2 bg-[#007BFF] rounded-full opacity-70"></span>
            </h2>

            @if($jadwalsByDate->isNotEmpty())
                <!-- Date Tabs -->
                <div class="flex flex-wrap justify-center gap-3 lg:gap-4 mb-12"> {{-- Adjusted gap --}}
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
                            class="jadwal-date-btn flex-shrink-0 w-36 sm:w-44 bg-white hover:bg-[#EBF7FF] border border-gray-200 hover:border-[#007BFF] rounded-lg p-4 transition-all duration-300 text-center group shadow-sm hover:shadow-md"> {{-- Smaller button, adjusted padding and shadow --}}
                            <div class="text-sm text-gray-600 group-hover:text-[#007BFF] mb-0.5">
                                @if($isToday)
                                    Hari Ini
                                @elseif($isTomorrow)
                                    Besok
                                @else
                                    {{ $date->format('l') }}
                                @endif
                            </div>
                            <div class="text-lg font-bold text-[#2C3E50] group-hover:text-[#007BFF]">
                                {{ $date->format('d M Y') }}
                            </div>
                            @if($isWeekend)
                                <div class="text-xs text-[#FFC107] group-hover:text-[#2C3E50] mt-1 font-medium">Weekend</div>
                            @endif
                            <div class="text-xs text-gray-500 group-hover:text-gray-700 mt-2">
                                {{ $jadwals->count() }} jadwal
                            </div>
                        </button>
                    @endforeach
                </div>

                <!-- Jadwal Content per Date -->
                @foreach($jadwalsByDate as $tanggal => $jadwals)
                    <div class="jadwal-content hidden" data-date="{{ $tanggal }}">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-6"> {{-- More columns for time slots --}}
                            @foreach($jadwals as $jadwal)
                                <a href="{{ route('booking.kursi', $jadwal->jadwal_id) }}" 
                                   class="block bg-white hover:bg-[#EBF7FF] border border-gray-200 hover:border-[#007BFF] rounded-lg p-5 transition-all duration-300 group hover:scale-[1.02] shadow-sm hover:shadow-md text-center"> {{-- Adjusted padding, shadow, and centered text --}}
                                    <div class="mb-3">
                                        <span class="text-3xl font-extrabold text-[#2C3E50] group-hover:text-[#007BFF]">
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-center gap-2 text-sm text-gray-500 group-hover:text-gray-700 mb-2"> {{-- Centered icon and text --}}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        {{ $jadwal->studio->nama_studio }}
                                    </div>
                                    <div class="text-lg font-bold text-[#007BFF] group-hover:text-[#2C3E50] mb-3"> {{-- Adjusted font size and spacing --}}
                                        Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }}
                                    </div>
                                    <div class="inline-flex items-center text-sm font-semibold text-gray-500 group-hover:text-[#007BFF] transition-colors duration-200">
                                        Pilih Kursi <i class="fas fa-arrow-right ml-2 text-xs"></i> {{-- Added icon --}}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white rounded-xl p-12 text-center shadow-sm border border-gray-200 max-w-lg mx-auto"> {{-- Adjusted padding, shadow, and max-width --}}
                    <div class="text-5xl mb-4 text-gray-400">📅</div> {{-- Made icon color subtle --}}
                    <h3 class="text-xl font-bold text-[#2C3E50] mb-3">Tidak Ada Jadwal Tayang</h3> {{-- Adjusted text size --}}
                    <p class="text-gray-600 text-base">
                        Film ini belum memiliki jadwal tayang. Silakan cek kembali nanti atau jelajahi film lainnya.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Font Awesome for Icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateButtons = document.querySelectorAll('.jadwal-date-btn');
        const jadwalContents = document.querySelectorAll('.jadwal-content');
        
        window.showJadwal = function(tanggal) {
            jadwalContents.forEach(content => content.classList.add('hidden'));
            dateButtons.forEach(btn => {
                // Reset button styles
                btn.classList.remove('bg-[#007BFF]', 'text-white', 'border-[#007BFF]', 'shadow-md');
                btn.classList.add('bg-white', 'border-gray-200', 'shadow-sm', 'hover:bg-[#EBF7FF]', 'hover:border-[#007BFF]', 'hover:shadow-md'); 

                // Reset text colors within button
                btn.querySelector('div:nth-child(1)').classList.remove('text-white');
                btn.querySelector('div:nth-child(1)').classList.add('text-gray-600');
                btn.querySelector('div:nth-child(2)').classList.remove('text-white');
                btn.querySelector('div:nth-child(2)').classList.add('text-[#2C3E50]');
                const weekendText = btn.querySelector('div:nth-child(3)');
                if (weekendText) { 
                    weekendText.classList.remove('text-white');
                    weekendText.classList.add('text-[#FFC107]');
                }
                btn.querySelector('div:last-child').classList.remove('text-white');
                btn.querySelector('div:last-child').classList.add('text-gray-500');
            });

            const selectedContent = document.querySelector(`.jadwal-content[data-date="${tanggal}"]`);
            if (selectedContent) selectedContent.classList.remove('hidden');

            const selectedButton = document.querySelector(`.jadwal-date-btn[data-date="${tanggal}"]`);
            if (selectedButton) {
                // Apply selected button styles
                selectedButton.classList.remove('bg-white', 'border-gray-200', 'shadow-sm', 'hover:bg-[#EBF7FF]', 'hover:border-[#007BFF]', 'hover:shadow-md');
                selectedButton.classList.add('bg-[#007BFF]', 'text-white', 'border-[#007BFF]', 'shadow-md');

                // Apply selected text colors within button
                selectedButton.querySelector('div:nth-child(1)').classList.remove('text-gray-600');
                selectedButton.querySelector('div:nth-child(1)').classList.add('text-white');
                selectedButton.querySelector('div:nth-child(2)').classList.remove('text-[#2C3E50]');
                selectedButton.querySelector('div:nth-child(2)').classList.add('text-white');
                const weekendText = selectedButton.querySelector('div:nth-child(3)');
                if (weekendText) { 
                    weekendText.classList.remove('text-[#FFC107]');
                    weekendText.classList.add('text-white'); 
                }
                selectedButton.querySelector('div:last-child').classList.remove('text-gray-500');
                selectedButton.querySelector('div:last-child').classList.add('text-white');
            }
        };

        if (dateButtons.length > 0) {
            const firstDate = dateButtons[0].getAttribute('data-date');
            showJadwal(firstDate);
        }
    });
</script>
@endsection