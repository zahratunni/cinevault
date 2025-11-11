@extends('layouts.app')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen pb-20">

    <!-- Main Content Section: Film Details -->
    <div class="relative bg-white py-16 lg:py-24 shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16 items-start">

                <!-- Left: Poster -->
                <div class="lg:col-span-1 flex justify-center">
                    <div class="sticky top-28 w-full max-w-sm lg:max-w-none">
                        <img src="{{ asset($film->poster_url) }}"
                             alt="{{ $film->judul }}"
                             class="w-full aspect-[2/3] object-cover rounded-xl shadow-[0_0_25px_rgba(0,123,255,0.2)]
                              border border-gray-200 transform hover:scale-[1.01] transition-transform duration-300">
                    </div>
                </div>

                <!-- Right: Film Details -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Title & Rating & Genres -->
                    <div>
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <h1 class="text-4xl lg:text-5xl font-extrabold text-[#2C3E50] tracking-tight leading-tight">
                                {{ $film->judul }}
                            </h1>
                            <span class="bg-[#007BFF] text-white px-4 py-1.5 rounded-lg font-bold text-lg shadow-sm">
                                {{ $film->rating }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3 flex-wrap text-sm">
                            <span class="bg-[#E3F2FD] text-[#007BFF] px-4 py-1.5 rounded-full font-semibold">{{ $film->genre }}</span>
                            <span class="bg-gray-200 text-gray-700 px-4 py-1.5 rounded-full font-semibold">{{ $film->durasi_menit }} min</span>
                        </div>
                    </div>

                    <!-- Trailer Button -->
                    @if($film->trailer_url)
                    <div class="pt-2">
                        <a href="{{ $film->trailer_url }}" target="_blank"
                           class="inline-flex items-center gap-3 bg-[#007BFF] hover:bg-[#0056B3] text-white font-bold px-8 py-3.5 
                           rounded-full transition-all duration-300 shadow-[0_0_15px_rgba(0,123,255,0.4)] hover:scale-105">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                            </svg>
                            Watch Trailer
                        </a>
                    </div>
                    @endif

                    <!-- Sinopsis -->
                    <div class="pt-4 border-t border-gray-100">
                        <h2 class="text-2xl font-bold text-[#2C3E50] mb-4 border-l-4 border-[#007BFF] pl-4">
                            Sinopsis
                        </h2>
                        <p class="text-gray-700 leading-relaxed text-base lg:text-lg">
                            {{ $film->sinopsis }}
                        </p>
                    </div>

                    <!-- Film Information -->
                    <div class="pt-4 border-t border-gray-100">
                        <h2 class="text-2xl font-bold text-[#2C3E50] mb-5 border-l-4 border-[#007BFF] pl-4">
                            Film Information
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
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

                        <div class="pt-6 mt-6 border-t border-gray-100">
                            <p class="text-gray-500 text-sm font-semibold mb-2">CAST</p>
                            <p class="text-[#2C3E50] leading-relaxed text-base">{{ $film->cast_list ?? '-' }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Tayang Section -->
    <div class="bg-[#F9FAFB] py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-[#2C3E50] mb-10 text-center relative pb-3">
                <span class="relative z-10">Jadwal Tayang</span>
                <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-24 h-2 bg-[#007BFF] rounded-full opacity-70"></span>
            </h2>

            @if($jadwalsByDate->isNotEmpty())
                
                @php
                    $today = today();
                    $tomorrow = today()->addDay();
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">
                    <!-- Kalender (Left Side) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                            <!-- Header Kalender -->
                            <div class="flex items-center justify-between mb-6">
                                <button onclick="previousMonth()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <h3 class="text-lg font-bold text-[#2C3E50]" id="currentMonth">
                                    {{ now()->translatedFormat('F Y') }}
                                </h3>
                                <button onclick="nextMonth()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Kalender Grid -->
                            <div class="mb-4">
                                <!-- Header Hari -->
                                <div class="grid grid-cols-7 gap-1 mb-2">
                                    <div class="text-center text-xs font-bold text-gray-500 py-2">Min</div>
                                    <div class="text-center text-xs font-bold text-gray-500 py-2">Sen</div>
                                    <div class="text-center text-xs font-bold text-gray-500 py-2">Sel</div>
                                    <div class="text-center text-xs font-bold text-gray-500 py-2">Rab</div>
                                    <div class="text-center text-xs font-bold text-gray-500 py-2">Kam</div>
                                    <div class="text-center text-xs font-bold text-gray-500 py-2">Jum</div>
                                    <div class="text-center text-xs font-bold text-gray-500 py-2">Sab</div>
                                </div>
                                
                                <!-- Tanggal -->
                                <div class="grid grid-cols-7 gap-1" id="calendarDates">
                                    <!-- Generated by JavaScript -->
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="pt-4 border-t text-center">
                                <p class="text-sm text-gray-600">Klik tanggal untuk melihat jadwal</p>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal (Right Side) -->
                    <div class="lg:col-span-3">
                        @foreach($jadwalsByDate as $tanggal => $jadwals)
                            @php
                                $date = \Carbon\Carbon::parse($tanggal);
                                $isToday = $date->isToday();
                                $isTomorrow = $date->isTomorrow();
                                $isClickable = $isToday || $isTomorrow;
                            @endphp

                            <div class="jadwal-content hidden" data-date="{{ $tanggal }}">
                                <!-- Header Tanggal -->
                                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-bold text-[#007BFF] mb-1">
                                                @if($isToday)
                                                    HARI INI
                                                @elseif($isTomorrow)
                                                    BESOK
                                                @else
                                                    {{ strtoupper($date->translatedFormat('l')) }}
                                                @endif
                                            </div>
                                            <h3 class="text-3xl font-extrabold text-[#2C3E50]">
                                                {{ $date->translatedFormat('d F Y') }}
                                            </h3>
                                            <p class="text-gray-600 mt-1">{{ $jadwals->count() }} jadwal tersedia</p>
                                        </div>
                                        
                                        @if(!$isClickable)
                                            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                     d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                <span class="font-bold">Belum Bisa Dipesan</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Grid Jadwal -->
                                @if($isClickable)
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($jadwals as $jadwal)
                                            <a href="{{ route('booking.kursi', $jadwal->jadwal_id) }}" 
                                               class="block bg-white hover:bg-[#EBF7FF] border-2 border-gray-200 hover:border-[#007BFF] rounded-xl p-6 transition-all duration-300 
                                               group hover:scale-105 shadow-sm hover:shadow-lg text-center">
                                                <div class="mb-4">
                                                    <span class="text-4xl font-extrabold text-[#2C3E50] group-hover:text-[#007BFF]">
                                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-center gap-2 text-sm text-gray-500 group-hover:text-gray-700 mb-4">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 
                                                        4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    <span class="font-medium">{{ $jadwal->studio->nama_studio }}</span>
                                                </div>
                                                <div class="inline-flex items-center text-sm font-bold text-[#007BFF] group-hover:text-[#0056B3] 
                                                transition-colors duration-200">
                                                    Pilih Kursi <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-8 text-center">
                                        <svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-
                                            .77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <h3 class="text-xl font-bold text-yellow-800 mb-2">Belum Bisa Dipesan</h3>
                                        <p class="text-yellow-700 mb-1">Pemesanan untuk tanggal ini belum dibuka.</p>
                                        <p class="text-yellow-700 font-semibold">Silakan pilih <strong>Hari Ini</strong> atau 
                                        <strong>Besok</strong> di kalender.</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            @else
                <div class="bg-white rounded-xl p-12 text-center shadow-sm border border-gray-200 max-w-lg mx-auto">
                    <div class="text-5xl mb-4 text-gray-400">📅</div>
                    <h3 class="text-xl font-bold text-[#2C3E50] mb-3">Tidak Ada Jadwal Tayang</h3>
                    <p class="text-gray-600 text-base">
                        Film ini belum memiliki jadwal tayang. Silakan cek kembali nanti atau jelajahi film lainnya.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script>
    // Data jadwal dari backend
    const jadwalDates = @json(array_keys($jadwalsByDate->toArray()));
    const today = new Date('{{ today()->format('Y-m-d') }}');
    const tomorrow = new Date('{{ today()->addDay()->format('Y-m-d') }}');

    let currentDate = new Date();

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        // Update header
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        const calendarDates = document.getElementById('calendarDates');
        calendarDates.innerHTML = '';
        
        // Empty cells before first day
        for (let i = 0; i < firstDay; i++) {
            calendarDates.innerHTML += '<div></div>';
        }
        
        // Days
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const currentDateObj = new Date(dateStr);
            
            const hasSchedule = jadwalDates.includes(dateStr);
            const isToday = dateStr === today.toISOString().split('T')[0];
            const isTomorrow = dateStr === tomorrow.toISOString().split('T')[0];
            const isClickable = isToday || isTomorrow;
            const isPast = currentDateObj < today;
            
            let classes = 'aspect-square flex items-center justify-center rounded-lg text-sm font-semibold transition-all relative';
            
            // Tampilkan semua tanggal dengan jadwal, tapi yang lalu disabled
            if (hasSchedule && isPast) {
                // Ada jadwal tapi sudah lewat (kemarin-kemarin)
                classes += ' bg-gray-200 text-gray-500 cursor-not-allowed line-through';
            } else if (hasSchedule && isClickable) {
                // Ada jadwal dan bisa diklik (hari ini/besok)
                classes += ' bg-white hover:bg-[#007BFF] hover:text-white border-2 border-[#007BFF] text-[#007BFF] cursor-pointer font-bold';
            } else if (hasSchedule) {
                // Ada jadwal tapi belum bisa dipesan (lusa dst)
                classes += ' bg-white border-2 border-gray-300 text-gray-500 cursor-not-allowed';
            } else if (isPast) {
                // Tanggal lewat tanpa jadwal
                classes += ' bg-gray-50 text-gray-300 cursor-not-allowed';
            } else {
                // Tanggal depan tanpa jadwal
                classes += ' bg-gray-50 text-gray-400 cursor-not-allowed';
            }
            
            const onclick = hasSchedule && isClickable ? `onclick="showJadwal('${dateStr}')"` : '';
            
            // Tambah dot indicator untuk tanggal yang ada jadwal
            const dotIndicator = hasSchedule ? '<div class="absolute bottom-0.5 w-1.5 h-1.5 bg-current rounded-full"></div>' : '';
            
            calendarDates.innerHTML += `
                <div class="${classes}" ${onclick} data-date="${dateStr}" title="${hasSchedule ? 
                (isPast ? 'Jadwal sudah lewat' : (isClickable ? 'Klik untuk lihat jadwal' : 'Belum bisa dipesan')) : 'Tidak ada jadwal'}">
                    ${day}
                    ${dotIndicator}
                </div>
            `;
        }
    }

    function showJadwal(tanggal) {
        // Hide all content
        document.querySelectorAll('.jadwal-content').forEach(el => el.classList.add('hidden'));
        
        // Show selected
        const selectedContent = document.querySelector(`.jadwal-content[data-date="${tanggal}"]`);
        if (selectedContent) {
            selectedContent.classList.remove('hidden');
            window.scrollTo({ top: selectedContent.offsetTop - 100, behavior: 'smooth' });
        }
        
        // Update calendar active state
        document.querySelectorAll('#calendarDates > div[data-date]').forEach(el => {
            const hasSchedule = jadwalDates.includes(el.getAttribute('data-date'));
            const dateStr = el.getAttribute('data-date');
            const currentDateObj = new Date(dateStr);
            const isToday = dateStr === today.toISOString().split('T')[0];
            const isTomorrow = dateStr === tomorrow.toISOString().split('T')[0];
            const isClickable = isToday || isTomorrow;
            const isPast = currentDateObj < today;
            
            // Reset to default state
            if (hasSchedule && isPast) {
                el.className = 'aspect-square flex items-center justify-center rounded-lg text-sm font-semibold transition-all relative bg-gray-200 text-gray-500 cursor-not-allowed line-through';
            } else if (hasSchedule && isClickable) {
                el.className = 'aspect-square flex items-center justify-center rounded-lg text-sm font-semibold transition-all relative bg-white hover:bg-[#007BFF] hover:text-white border-2 border-[#007BFF] text-[#007BFF] cursor-pointer font-bold';
            } else if (hasSchedule) {
                el.className = 'aspect-square flex items-center justify-center rounded-lg text-sm font-semibold transition-all relative bg-white border-2 border-gray-300 text-gray-500 cursor-not-allowed';
            }
        });
        
        const selectedDate = document.querySelector(`#calendarDates > div[data-date="${tanggal}"]`);
        if (selectedDate) {
            selectedDate.classList.remove('border-[#007BFF]', 'text-[#007BFF]', 'hover:bg-[#007BFF]', 'hover:text-white');
            selectedDate.classList.add('bg-[#007BFF]', 'text-white', 'shadow-lg');
        }
    }

    function previousMonth() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    }

    function nextMonth() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderCalendar();
        
        // Auto select today if available
        const todayStr = today.toISOString().split('T')[0];
        if (jadwalDates.includes(todayStr)) {
            showJadwal(todayStr);
        } else if (jadwalDates.length > 0) {
            // Select first available clickable date
            const clickableDates = jadwalDates.filter(date => {
                const d = new Date(date);
                return d >= today && d <= tomorrow;
            });
            if (clickableDates.length > 0) {
                showJadwal(clickableDates[0]);
            }
        }
    });
</script>
@endsection