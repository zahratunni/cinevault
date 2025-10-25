@extends('layouts.app')

@section('content')
<div class="bg-[#F9FAFB] py-24 px-8"> {{-- Updated main background and padding-top to match home section --}}
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex items-center justify-between mb-16"> {{-- Increased bottom margin for consistency --}}
            <h2 class="text-4xl md:text-5xl font-extrabold text-[#2C3E50] tracking-tight pb-3 relative"> {{-- Updated text style to match home --}}
                <span class="relative z-10">Now Playing</span>
                <span class="absolute bottom-0 left-0 w-24 h-2 bg-[#FFC107] rounded-full -z-0 opacity-70"></span> {{-- Accent underline from home --}}
            </h2>
        </div>

        <!-- Film Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse($films as $film)
                <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl border border-gray-100 hover:border-[#007BFF] transition-all duration-500 transform hover:-translate-y-2 group h-full"> {{-- Updated card background, shadow, and border --}}
                    <!-- Poster -->
                    <div class="aspect-[2/3] relative overflow-hidden">
                        @if($film->poster_url)
                            <img src="{{ asset($film->poster_url) }}" 
                                 alt="{{ $film->judul }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center bg-gray-100 text-gray-500"> {{-- Updated placeholder --}}
                                <div class="text-center">
                                    <div class="text-4xl mb-2">🎬</div>
                                    <p class="text-sm">No Poster</p>
                                </div>
                            </div>
                        @endif
                        <span class="absolute top-4 left-4 bg-[#007BFF] text-white text-sm font-bold px-4 py-1.5 rounded-full shadow-md"> {{-- Rating badge from home --}}
                            {{ $film->rating }} <i class="fas fa-star ml-1"></i>
                        </span>
                    </div>

                    <!-- Detail -->
                    <div class="p-5"> {{-- Increased padding for consistency --}}
                        <h3 class="text-[#2C3E50] font-extrabold text-xl truncate mb-3">{{ $film->judul }}</h3> {{-- Updated text style --}}
                        
                        <div class="flex flex-wrap gap-3 mb-5"> {{-- Changed to flex-wrap for better responsiveness --}}
                            <span class="bg-[#E3F2FD] text-[#007BFF] text-xs px-3 py-1.5 rounded-full font-medium">{{ $film->genre }}</span> {{-- Updated badge style --}}
                            <span class="bg-[#FFFDE7] text-[#FFC107] text-xs px-3 py-1.5 rounded-full font-medium">{{ $film->durasi_menit }} min</span> {{-- Updated badge style --}}
                        </div>

                        <a href="{{ route('film.show', $film->film_id) }}" 
                           class="block w-full bg-gradient-to-r from-[#007BFF] to-[#0056B3] text-white font-semibold py-3.5 text-base rounded-lg 
                                  hover:opacity-90 transition-opacity duration-300 text-center shadow-md"> {{-- Updated button style to match home --}}
                            Beli Tiket <i class="fas fa-ticket-alt ml-2 text-sm"></i>
                        </a>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500 py-12">Belum ada film yang sedang tayang</p> {{-- Updated placeholder text color --}}
            @endforelse
        </div>
    </div>
</div>
@endsection