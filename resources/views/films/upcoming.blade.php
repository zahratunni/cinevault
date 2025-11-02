@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white text-[#2C3E50] py-20 px-6 lg:px-8"> {{-- Menggunakan min-h-screen dan padding dari home --}}
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16"> {{-- Disesuaikan dengan header section di home --}}
            <span class="inline-block text-[#007BFF] text-sm font-bold tracking-wider uppercase mb-3">Akan Datang</span>
            <h2 class="text-5xl md:text-6xl font-black text-[#2C3E50]">Coming Soon</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6"> {{-- Menggunakan grid 1-5 kolom seperti home --}}
            @forelse($films as $film)
                <div class="group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 h-full flex flex-col border border-gray-100"> {{-- Card styling disesuaikan dengan upcoming section di home --}}
                    <div class="aspect-[2/3] relative overflow-hidden bg-gray-50"> {{-- Placeholder background --}}
                        @if($film->poster_url)
                            <img src="{{ asset($film->poster_url) }}"
                                 alt="{{ $film->judul }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                <i class="fas fa-film text-4xl text-gray-300"></i>
                            </div>
                        @endif
                        <div class="absolute top-3 right-3 bg-amber-400 text-white text-xs font-bold px-3 py-1.5 rounded-full"> {{-- Badge "Soon" dari home --}}
                            <i class="far fa-calendar mr-1"></i>Soon
                        </div>
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="text-[#2C3E50] font-bold text-base mb-3 line-clamp-2 leading-snug"> {{-- Judul film dari home --}}
                            {{ $film->judul }}
                        </h3>
                        <div class="flex flex-wrap gap-2 mb-4 text-xs"> {{-- Genre dan durasi dari home --}}
                            <span class="bg-[#E3F2FD] text-[#007BFF] px-3 py-1 rounded-full font-medium">{{ $film->genre }}</span>
                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium">{{ $film->durasi_menit }} min</span>
                        </div>
                        <a href="{{ route('film.show', $film->film_id) }}"
                           class="mt-auto w-full bg-[#007BFF] text-white text-center font-bold py-3 rounded-full hover:bg-[#0056B3] transition-all duration-300"> {{-- Button "Lihat Detail" dari home --}}
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12"> {{-- Pesan kosong dari home --}}
                    <i class="far fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada film upcoming</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection