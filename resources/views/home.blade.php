@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white text-[#2C3E50]">

    {{-- Hero Section - Minimalist Design --}}
    <div class="relative w-full h-screen overflow-hidden bg-gradient-to-br from-[#007BFF] via-[#0056B3] to-[#003F8C]">
        {{-- Subtle Pattern Overlay --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 50px 50px;"></div>
        </div>

        {{-- Background Slider --}}
        <div class="swiper heroSwiper w-full h-full absolute inset-0 z-0 opacity-20">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('7.jpg') }}" class="w-full h-full object-cover" alt="Movie Banner">
                </div>
                <div class="swiper-slide">
                    <div class="w-full h-full bg-cover bg-center" style="background-image: url
                    ('https://via.placeholder.com/1920x1080/0056B3/FFFFFF?text=Movie+Banner+2');"></div>
                </div>
                <div class="swiper-slide">
                    <div class="w-full h-full bg-cover bg-center" style="background-image: url
                    ('https://via.placeholder.com/1920x1080/003F8C/FFFFFF?text=Movie+Banner+3');"></div>
                </div>
            </div>
        </div>

        {{-- Hero Content --}}
        <div class="absolute inset-0 z-20 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black text-white leading-tight mb-4 sm:mb-6">
                        Cinema<br/>
                        <span class="text-[#66CCFF]">Experience</span>
                    </h1>
                    <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-white/90 mb-6 sm:mb-8 lg:mb-10 font-light px-4">
                        Pesan tiket dengan mudah, nikmati film terbaik
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
                        <a href="#playing-now" class="inline-flex items-center justify-center bg-white text-[#007BFF] font-bold py-3 sm:py-4 px-8 sm:px-10 
                        rounded-full hover:bg-[#66CCFF] hover:text-white transition-all duration-300 shadow-xl text-sm sm:text-base">
                            Jelajahi Film
                            <i class="fas fa-arrow-right ml-2 sm:ml-3"></i>
                        </a>
                        <a href="#why-choose" class="inline-flex items-center justify-center bg-transparent border-2 border-white text-white 
                        font-bold py-3 sm:py-4 px-8 sm:px-10 rounded-full hover:bg-white hover:text-[#007BFF] transition-all duration-300 text-sm sm:text-base">
                            Pelajari Lebih
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-6 sm:bottom-10 left-1/2 transform -translate-x-1/2 z-30 hidden sm:block">
            <div class="w-6 h-10 border-2 border-white/50 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white/50 rounded-full mt-2 animate-bounce"></div>
            </div>
        </div>
    </div>

    {{-- Playing Now Section --}}
    <div id="playing-now" class="py-20 px-6 lg:px-8 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-block text-[#007BFF] text-sm font-bold tracking-wider uppercase mb-3">Sedang Tayang</span>
                <h2 class="text-5xl md:text-6xl font-black text-[#2C3E50]">Now Playing</h2>
            </div>

            {{-- Movie Grid --}}
            <div class="swiper playingNowSwiper mb-12">
                <div class="swiper-wrapper">
                    @forelse($playingNow as $film)
                    <div class="swiper-slide" style="width: 260px;">
                        <div class="group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 h-full flex flex-col">
                            <div class="aspect-[2/3] relative overflow-hidden bg-gray-50">
                                @if($film->poster_url)
                                    <img src="{{ asset($film->poster_url) }}" alt="{{ $film->judul }}" class="w-full h-full object-cover group-hover:scale-105 
                                    transition-transform duration-700">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <i class="fas fa-film text-4xl text-gray-300"></i>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm text-[#007BFF] text-xs font-bold px-3 py-1.5 rounded-full">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>{{ $film->rating }}
                                </div>
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-[#2C3E50] font-bold text-base mb-3 line-clamp-2 leading-snug">
                                    {{ $film->judul }}
                                </h3>
                                <div class="flex flex-wrap gap-2 mb-4 text-xs">
                                    <span class="bg-[#E3F2FD] text-[#007BFF] px-3 py-1 rounded-full font-medium">{{ $film->genre }}</span>
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium">{{ $film->durasi_menit }} min</span>
                                </div>
                                <a href="{{ route('film.show', $film->film_id) }}" class="mt-auto w-full bg-[#007BFF] text-white text-center font-bold py-3
                                 rounded-full hover:bg-[#0056B3] 
                                transition-all duration-300">
                                    Beli Tiket
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-film text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada film yang sedang tayang</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('films.playingNow') }}" class="inline-flex items-center bg-[#007BFF] text-white font-bold py-4 px-8 rounded-full 
                hover:bg-[#0056B3] transition-all duration-300 shadow-lg hover:shadow-xl group">
                    Lihat Semua Film
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Upcoming Section --}}
    <div class="py-20 px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-block text-[#007BFF] text-sm font-bold tracking-wider uppercase mb-3">Akan Datang</span>
                <h2 class="text-5xl md:text-6xl font-black text-[#2C3E50]">Coming Soon</h2>
            </div>

            <div class="swiper upcomingSwiper mb-12">
                <div class="swiper-wrapper">
                    @forelse($upcoming as $film)
                    <div class="swiper-slide" style="width: 260px;">
                        <div class="group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 h-full flex flex-col border border-gray-100">
                            <div class="aspect-[2/3] relative overflow-hidden bg-gray-50">
                                @if($film->poster_url)
                                    <img src="{{ asset($film->poster_url) }}" alt="{{ $film->judul }}" class="w-full h-full object-cover group-hover:scale-105 
                                    transition-transform duration-700">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <i class="fas fa-film text-4xl text-gray-300"></i>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3 bg-amber-400 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                                    <i class="far fa-calendar mr-1"></i>Soon
                                </div>
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-[#2C3E50] font-bold text-base mb-3 line-clamp-2 leading-snug">
                                    {{ $film->judul }}
                                </h3>
                                <div class="flex flex-wrap gap-2 mb-4 text-xs">
                                    <span class="bg-[#E3F2FD] text-[#007BFF] px-3 py-1 rounded-full font-medium">{{ $film->genre }}</span>
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium">{{ $film->durasi_menit }} min</span>
                                </div>
                                <a href="{{ route('film.show', $film->film_id) }}" class="mt-auto w-full bg-[#007BFF] text-white text-center font-bold py-3 
                                rounded-full hover:bg-[#0056B3] transition-all duration-300">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <i class="far fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada film upcoming</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('films.upcoming') }}" class="inline-flex items-center bg-[#007BFF] text-white font-bold py-4 px-8 rounded-full hover:bg-[#0056B3]
                 transition-all duration-300 shadow-lg hover:shadow-xl group">
                    Lihat Semua Film
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Why Choose Section --}}
    <div id="why-choose" class="py-20 px-6 lg:px-8 bg-gradient-to-b from-[#F8FAFC] to-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-block text-[#007BFF] text-sm font-bold tracking-wider uppercase mb-3">Keunggulan Kami</span>
                <h2 class="text-5xl md:text-6xl font-black text-[#2C3E50] mb-4">
                    Kenapa <span class="text-[#007BFF]">CineVault</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $features = [
                        ['fas fa-bolt', 'Booking Cepat', 'Pesan tiket dalam hitungan detik'],
                        ['fas fa-shield-alt', 'Aman Terpercaya', 'Pembayaran dengan enkripsi tingkat bank'],
                        ['fas fa-film', 'Film Lengkap', 'Update jadwal setiap hari'],
                        ['fas fa-headset', 'Support 24/7', 'Tim siap membantu kapan saja'],
                    ];
                @endphp

                @foreach($features as $item)
                    <div class="text-center p-8 bg-white rounded-3xl border border-gray-100 hover:border-[#007BFF] hover:shadow-lg transition-all duration-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-[#007BFF] to-[#0056B3] rounded-2xl text-white text-2xl 
                        mb-5 shadow-lg">
                            <i class="{{ $item[0] }}"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#2C3E50] mb-2">{{ $item[1] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $item[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Styles and Scripts --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
html { scroll-behavior: smooth; }

/* Custom Scrollbar */
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: #f1f1f1; }
::-webkit-scrollbar-thumb { 
    background: linear-gradient(to bottom, #007BFF, #0056B3); 
    border-radius: 4px; 
}
::-webkit-scrollbar-thumb:hover { background: #003F8C; }

/* Swiper Custom Styles */
.swiper-slide { height: auto; }
</style>

<script>
// Hero Swiper
const heroSwiper = new Swiper('.heroSwiper', {
    loop: true,
    effect: 'fade',
    speed: 2000,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    fadeEffect: { crossFade: true },
});

// Playing Now Swiper
const playingNowSwiper = new Swiper('.playingNowSwiper', {
    slidesPerView: 'auto',
    spaceBetween: 20,
    loop: true,
    centeredSlides: false,
    autoplay: {
        delay: 3500,
        disableOnInteraction: false,
    },
    breakpoints: {
        640: { spaceBetween: 24 },
        1024: { spaceBetween: 30 },
    }
});

// Upcoming Swiper
const upcomingSwiper = new Swiper(".upcomingSwiper", {
    slidesPerView: 'auto',
    spaceBetween: 20,
    loop: true,
    centeredSlides: false,
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    breakpoints: {
        640: { spaceBetween: 24 },
        1024: { spaceBetween: 30 },
    },
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>
@endsection