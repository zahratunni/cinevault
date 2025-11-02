@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] py-16 sm:py-20 lg:py-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-12 sm:mb-16">
            <span class="inline-block text-[#007BFF] text-xs sm:text-sm font-bold tracking-wider uppercase mb-2 sm:mb-3">Sedang Tayang</span>
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-[#2C3E50] mb-3 sm:mb-4">
                Now Playing
            </h2>
        </div>

        <!-- Film Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-5 lg:gap-6">
            @forelse($films as $film)
                <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 group h-full">
                    <!-- Poster -->
                    <div class="aspect-[2/3] relative overflow-hidden bg-gray-50">
                        @if($film->poster_url)
                            <img src="{{ asset($film->poster_url) }}" 
                                 alt="{{ $film->judul }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                <i class="fas fa-film text-3xl sm:text-4xl text-gray-300"></i>
                            </div>
                        @endif
                        <span class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-white/95 backdrop-blur-sm text-[#007BFF] text-xs font-bold px-2 sm:px-3 py-1 sm:py-1.5 rounded-full">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>{{ $film->rating }}
                        </span>
                    </div>

                    <!-- Detail -->
                    <div class="p-4 sm:p-5">
                        <h3 class="text-[#2C3E50] font-bold text-sm sm:text-base mb-2 sm:mb-3 line-clamp-2 leading-snug">{{ $film->judul }}</h3>
                        
                        <div class="flex flex-wrap gap-1.5 sm:gap-2 mb-3 sm:mb-4 text-xs">
                            <span class="bg-[#E3F2FD] text-[#007BFF] px-2 sm:px-3 py-1 rounded-full font-medium">{{ $film->genre }}</span>
                            <span class="bg-gray-100 text-gray-600 px-2 sm:px-3 py-1 rounded-full font-medium">{{ $film->durasi_menit }} min</span>
                        </div>

                        <a href="{{ route('film.show', $film->film_id) }}" 
                           class="block w-full bg-[#007BFF] text-white font-bold py-2.5 sm:py-3 text-xs sm:text-sm rounded-full hover:bg-[#0056B3] transition-all duration-300 text-center">
                            Beli Tiket
                        </a>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500 py-12 sm:py-16">Belum ada film yang sedang tayang</p>
            @endforelse
        </div>
    </div>
</div>
@endsection