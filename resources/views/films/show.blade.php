@extends('layouts.app')

@section('content')

<div class="bg-black min-h-screen">

<!-- Main Content Section -->
<div class="relative bg-black py-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
            
            <!-- Left: Poster (2 columns) -->
            <div class="lg:col-span-2">
                <div class="sticky top-24">
                    <img src="{{ asset($film->poster_url) }}" 
                         alt="{{ $film->judul }}" 
                         class="w-full rounded-2xl shadow-2xl">
                </div>
            </div>
            
            <!-- Right: Film Details (3 columns) -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- Title & Rating -->
                <div>
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <h1 class="text-5xl font-bold text-white leading-tight">{{ $film->judul }}</h1>
                        <span class="bg-white text-black px-4 py-2 rounded-lg font-bold text-xl whitespace-nowrap">
                            {{ $film->rating }}
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="bg-[#FEA923] text-black px-4 py-2 rounded-lg font-semibold">
                            {{ $film->genre }}
                        </span>
                        <span class="bg-gray-800 text-white px-4 py-2 rounded-lg font-semibold">
                            {{ $film->durasi_menit }} min
                        </span>
                    </div>
                </div>
                
                <!-- Trailer Button -->
                @if($film->trailer_url)
                <div>
                    <a href="{{ $film->trailer_url }}" target="_blank"
                       class="inline-flex items-center gap-3 bg-[#FEA923] hover:bg-[#e69710] text-black font-bold px-8 py-4 rounded-full transition-all shadow-lg hover:scale-105">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                        </svg>
                        Watch Trailer
                    </a>
                </div>
                @endif
                
                <!-- Sinopsis -->
                <div>
                    <h2 class="text-2xl font-bold text-white mb-4">Sinopsis</h2>
                    <p class="text-gray-300 leading-relaxed text-base">
                        {{ $film->sinopsis }}
                    </p>
                </div>
                
                <!-- Film Information -->
                <div>
                    <h2 class="text-2xl font-bold text-white mb-6">Film Information</h2>
                    <div class="space-y-4">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold mb-1">PRODUSER</p>
                                <p class="text-white">{{ $film->produser ?? '-' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-gray-500 text-sm font-semibold mb-1">SUTRADARA</p>
                                <p class="text-white">{{ $film->sutradara ?? '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold mb-1">PENULIS</p>
                                <p class="text-white">{{ $film->penulis ?? '-' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-gray-500 text-sm font-semibold mb-1">PRODUKSI</p>
                                <p class="text-white">{{ $film->produksi ?? '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="pt-2">
                            <p class="text-gray-500 text-sm font-semibold mb-2">CAST</p>
                            <p class="text-white leading-relaxed">{{ $film->cast_list ?? '-' }}</p>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Jadwal Tayang Section - Hanya Hari Ini -->
<div class="bg-black border-t border-gray-900 py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-4xl font-bold text-white">Jadwal Tayang Hari Ini</h2>
            <div class="text-right">
                <div class="text-sm text-gray-400">{{ \Carbon\Carbon::now()->format('l') }}</div>
                <div class="text-2xl font-bold text-[#FEA923]">{{ \Carbon\Carbon::now()->format('d F Y') }}</div>
                @if(\Carbon\Carbon::now()->isWeekend())
                    <div class="text-sm text-[#FEA923] mt-1">Weekend</div>
                @endif
            </div>
        </div>
        
        @if($jadwalHariIni->isNotEmpty())
            <!-- Time Slots -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($jadwalHariIni as $jadwal)
                    {{-- PERBAIKAN: Menggunakan nama route baru 'booking.kursi' --}}
                    <a href="{{ route('booking.kursi', $jadwal->jadwal_id) }}" 
                       class="block bg-gray-900 hover:bg-[#FEA923] border-2 border-gray-800 hover:border-[#FEA923] rounded-xl p-6 transition-all group transform hover:scale-105">
                        <div class="mb-4">
                            <span class="text-4xl font-bold text-white group-hover:text-black">
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
        @else
            <!-- Tidak Ada Jadwal Hari Ini -->
            <div class="bg-gray-900 rounded-2xl p-12 text-center">
                <div class="text-6xl mb-4">📅</div>
                <h3 class="text-2xl font-bold text-white mb-3">Tidak Ada Jadwal Hari Ini</h3>
                <p class="text-gray-400 text-lg">
                    Film ini tidak tayang hari ini. Silakan cek kembali besok.
                </p>
            </div>
        @endif
    </div>
</div>