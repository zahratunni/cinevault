@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen text-white py-16">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Search Bar -->
        <form action="{{ route('films.index') }}" method="GET" class="mb-10 flex justify-center">
            <div class="relative w-full max-w-md">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari Film..." 
                    class="w-full px-5 py-3 rounded-full bg-white/10 backdrop-blur-sm text-white placeholder-gray-300 border border-white/20 focus:outline-none focus:border-[#FEA923] focus:bg-white/20 transition"
                >
                <button type="submit" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-[#FEA923]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Hasil Pencarian -->
        @if($films->isEmpty())
            <p class="text-center text-gray-400">
                Tidak ada film ditemukan di kategori <b>Playing Now</b> atau <b>Upcoming</b>.
            </p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($films as $film)
                    <div class="group bg-gray-900 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:scale-[1.02] transition duration-300">
                        <!-- Poster -->
                        <div class="relative">
                            <img src="{{ asset('storage/'.$film->poster) }}" 
                                 alt="{{ $film->judul }}" 
                                 class="w-full h-72 object-cover group-hover:opacity-90 transition">
                            <span class="absolute top-3 left-3 bg-[#FEA923] text-black text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $film->status_tayang }}
                            </span>
                        </div>

                        <!-- Detail Film -->
                        <div class="p-4">
                            <h3 class="text-lg font-bold mb-1 group-hover:text-[#FEA923] transition">
                                {{ $film->judul }}
                            </h3>
                            <p class="text-sm text-gray-400 mb-2">{{ Str::limit($film->deskripsi, 80) }}</p>

                            <div class="flex justify-between items-center">
                                <span class="text-[#FEA923] font-semibold">{{ $film->genre }}</span>
                               <a href="{{ route('film.show', $film->id) }}" 
   class="text-gray-300 hover:text-[#FEA923] text-sm font-medium">
   Lihat Detail →
</a>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
