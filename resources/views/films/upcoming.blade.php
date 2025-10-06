@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen text-white pt-32 pb-20 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-4xl font-bold text-white">Up coming</h1>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @forelse($films as $film)
                <div class="bg-[#1D1B1B] rounded-xl overflow-hidden border border-gray-800 hover:border-[#FEA923] transition group">
                    <div class="aspect-[2/3] bg-gray-800 relative overflow-hidden">
                        @if($film->poster_url)
                            <img src="{{ asset($film->poster_url) }}" 
                                 alt="{{ $film->judul }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-600">
                                <div class="text-center">
                                    <div class="text-4xl mb-2">🎬</div>
                                    <p class="text-sm">No Poster</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-white font-bold text-lg truncate">{{ $film->judul }}</h3>
                            <span class="text-white text-medium px-2 py-1 whitespace-nowrap">{{ $film->rating }}</span>
                        </div>
                        <div class="flex gap-2 mb-3 flex-wrap">
                            <span class="bg-[#403636] text-white text-xs px-2 py-1 rounded">{{ $film->genre }}</span>
                            <span class="bg-[#403636] text-white text-xs px-2 py-1 rounded">{{ $film->durasi_menit }} min</span>
                        </div>
                        <a href="{{ route('film.show', $film->film_id) }}" 
                           class="block w-full bg-[#FEA923] hover:bg-[#e69710] text-black font-semibold py-2 rounded-full transition text-center">
                            Lihat Film
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-400">Belum ada film upcoming</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
