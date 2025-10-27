@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F0F2F5] text-[#2C3E50] antialiased">

    {{-- Hero Section with Enhanced Design --}}
    <div class="relative w-full h-screen overflow-hidden">
        <!-- Background Slider -->
        <div class="swiper heroSwiper w-full h-full absolute inset-0 z-0">
            <div class="swiper-wrapper">
                {{-- Slide 1 --}}
                <div class="swiper-slide relative">
                    <img src="{{ asset('7.jpg') }}"
                         class="w-full h-full object-cover"
                         alt="Movie Banner">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
                </div>

                {{-- Slide 2 --}}
                <div class="swiper-slide relative">
                    <div class="w-full h-full bg-cover bg-center"
                         style="background-image: url('https://via.placeholder.com/1920x1080/0056B3/FFFFFF?text=Movie+Banner+2');">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
                    </div>
                </div>

                {{-- Slide 3 --}}
                <div class="swiper-slide relative">
                    <div class="w-full h-full bg-cover bg-center"
                         style="background-image: url('https://via.placeholder.com/1920x1080/003F8C/FFFFFF?text=Movie+Banner+3');">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hero Content --}}
        <div class="absolute inset-0 z-20 flex items-center">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full">
                <div class="max-w-3xl">
                    {{-- Main Heading --}}
                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[1.1] mb-6 animate__animated animate__fadeInLeft">
                        Nikmati Film Terbaik<br/>
                        <span class="text-[#66CCFF]">Setiap Saat</span>
                    </h1>

                    {{-- Description --}}
                    <p class="text-lg md:text-xl text-gray-300 mb-8 leading-relaxed max-w-2xl animate__animated animate__fadeInLeft animate__delay-1s">
                        Pesan tiket dengan mudah dan cepat. Tonton film favoritmu di bioskop terdekat.
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="#playing-now" class="group inline-flex items-center justify-center bg-gradient-to-r from-[#66CCFF] to-[#007BFF] text-white font-bold py-4 px-8 rounded-xl shadow-2xl hover:shadow-[#66CCFF]/50 transform hover:-translate-y-1 transition-all duration-300">
                            <span>Jelajahi Film</span>
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                        <a href="#why-choose" class="inline-flex items-center justify-center bg-white/10 backdrop-blur-sm border-2 border-white/30 text-white font-bold py-4 px-8 rounded-xl hover:bg-white/20 transition-all duration-300">
                            <span>Pelajari Lebih Lanjut</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Custom Pagination --}}
        <div class="swiper-pagination hero-pagination !bottom-8 z-30"></div>
    </div>

    {{-- Playing Now Section --}}
    <div id="playing-now" class="relative py-24 px-6 lg:px-8 bg-gradient-to-b from-white to-[#F9FAFB]">
        <div class="max-w-7xl mx-auto">
            {{-- Section Header --}}
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-12">
                <div>
                    <div class="inline-block bg-[#007BFF]/10 text-[#007BFF] text-sm font-bold px-4 py-1.5 rounded-full mb-3">
                        SEDANG TAYANG
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-[#2C3E50] mb-2">
                        Now Playing
                    </h2>
                </div>
                
                {{-- Navigation Buttons --}}
                <div class="flex gap-3 mt-6 md:mt-0">
                    <button class="swiper-button-prev-custom group bg-white text-gray-400 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl hover:bg-[#007BFF] hover:text-white transition-all duration-300 border border-gray-200">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="swiper-button-next-custom group bg-white text-gray-400 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl hover:bg-[#007BFF] hover:text-white transition-all duration-300 border border-gray-200">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            {{-- Movie Slider --}}
            <div class="swiper playingNowSwiper mb-12">
                <div class="swiper-wrapper">
                    @forelse($playingNow as $film)
                    <div class="swiper-slide h-auto">
                        <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 h-full flex flex-col">
                            {{-- Poster --}}
                            <div class="aspect-[2/3] relative overflow-hidden bg-gray-100">
                                @if($film->poster_url)
                                    <img src="{{ asset($film->poster_url) }}" alt="{{ $film->judul }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <i class="fas fa-film text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                                
                                {{-- Rating Badge --}}
                                <div class="absolute top-4 left-4 bg-[#007BFF] text-white text-sm font-bold px-3 py-1.5 rounded-xl shadow-lg">
                                    {{ $film->rating }}
                                </div>

                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                    <div class="text-white text-sm line-clamp-3">
                                        {{ $film->deskripsi ?? 'Deskripsi tidak tersedia' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-[#2C3E50] font-bold text-lg mb-3 line-clamp-2 leading-tight group-hover:text-[#007BFF] transition-colors">
                                    {{ $film->judul }}
                                </h3>
                                
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="bg-[#E3F2FD] text-[#007BFF] text-xs px-3 py-1 rounded-full font-semibold">
                                        {{ $film->genre }}
                                    </span>
                                    <span class="bg-[#FFF3E0] text-[#FF9800] text-xs px-3 py-1 rounded-full font-semibold">
                                        <i class="far fa-clock mr-1"></i>{{ $film->durasi_menit }} min
                                    </span>
                                </div>

                                <a href="{{ route('film.show', $film->film_id) }}"
                                    class="mt-auto group/btn flex items-center justify-center w-full bg-gradient-to-r from-[#007BFF] to-[#0056B3] text-white font-bold py-3 rounded-xl hover:shadow-lg hover:shadow-[#007BFF]/30 transition-all duration-300">
                                    <span>Beli Tiket</span>
                                    <i class="fas fa-arrow-right ml-2 group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-film text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada film yang sedang tayang</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- View All Button --}}
            <div class="text-center">
                <a href="{{ route('films.playingNow') }}"
                    class="inline-flex items-center bg-white text-[#007BFF] font-bold py-4 px-8 rounded-xl border-2 border-[#007BFF] hover:bg-[#007BFF] hover:text-white shadow-lg hover:shadow-xl transition-all duration-300 group">
                    <span>Lihat Semua Film</span>
                    <i class="fas fa-chevron-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Upcoming Section --}}
    <div class="py-24 px-6 lg:px-8 bg-gradient-to-b from-[#F9FAFB] to-white">
        <div class="max-w-7xl mx-auto">
            {{-- Section Header --}}
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-12">
                <div>
                    <div class="inline-block bg-[#007BFF]/10 text-[#007BFF] text-sm font-bold px-4 py-1.5 rounded-full mb-3">
                        AKAN DATANG
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-[#2C3E50] mb-2">
                        Upcoming
                    </h2>
                </div>
                
                <div class="flex gap-3 mt-6 md:mt-0">
                    <button class="swiper-button-prev-upcoming bg-white text-gray-400 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl hover:bg-[#007BFF] hover:text-white transition-all duration-300 border border-gray-200">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="swiper-button-next-upcoming bg-white text-gray-400 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl hover:bg-[#007BFF] hover:text-white transition-all duration-300 border border-gray-200">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="swiper upcomingSwiper mb-12">
                <div class="swiper-wrapper">
                    @forelse($upcoming as $film)
                    <div class="swiper-slide h-auto">
                        <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 h-full flex flex-col">
                            <div class="aspect-[2/3] relative overflow-hidden bg-gray-50">
                                @if($film->poster_url)
                                    <img src="{{ asset($film->poster_url) }}" alt="{{ $film->judul }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <i class="fas fa-film text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                                
                                <div class="absolute top-4 left-4 bg-[#FFC107] text-white text-sm font-bold px-3 py-1.5 rounded-xl shadow-lg">
                                    <i class="far fa-calendar mr-1"></i>Coming Soon
                                </div>

                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                    <div class="text-white text-sm line-clamp-3">
                                        {{ $film->deskripsi ?? 'Deskripsi tidak tersedia' }}
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-[#2C3E50] font-bold text-lg mb-3 line-clamp-2 leading-tight group-hover:text-[#007BFF] transition-colors">
                                    {{ $film->judul }}
                                </h3>
                                
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="bg-[#E3F2FD] text-[#007BFF] text-xs px-3 py-1 rounded-full font-semibold">
                                        {{ $film->genre }}
                                    </span>
                                    <span class="bg-[#FFF3E0] text-[#FF9800] text-xs px-3 py-1 rounded-full font-semibold">
                                        <i class="far fa-clock mr-1"></i>{{ $film->durasi_menit }} min
                                    </span>
                                </div>

                                <a href="{{ route('film.show', $film->film_id) }}"
                                    class="mt-auto group/btn flex items-center justify-center w-full bg-[#E3F2FD] text-[#007BFF] font-bold py-3 rounded-xl hover:bg-[#007BFF] hover:text-white transition-all duration-300 border-2 border-[#007BFF]/20 hover:border-[#007BFF]">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>Lihat Detail</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <i class="far fa-calendar-times text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada film upcoming</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('films.upcoming') }}"
                    class="inline-flex items-center bg-white text-[#007BFF] font-bold py-4 px-8 rounded-xl border-2 border-[#007BFF] hover:bg-[#007BFF] hover:text-white shadow-lg hover:shadow-xl transition-all duration-300 group">
                    <span>Lihat Semua Film</span>
                    <i class="fas fa-chevron-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Why Choose Section --}}
    <div id="why-choose" class="py-24 px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <div class="inline-block bg-[#007BFF]/10 text-[#007BFF] text-sm font-bold px-4 py-1.5 rounded-full mb-3">
                    KEUNGGULAN KAMI
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-[#2C3E50] mb-4">
                    Kenapa Memilih <span class="text-[#007BFF]">CineVault</span>
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Pengalaman menonton film terbaik dengan berbagai kemudahan dan keuntungan
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $features = [
                        ['fas fa-bolt', 'Booking Instan', 'Pesan tiket dalam hitungan detik tanpa antrian panjang. Konfirmasi langsung di aplikasi.', 'from-blue-500 to-cyan-500'],
                        ['fas fa-shield-alt', 'Pembayaran Aman', 'Berbagai metode pembayaran dengan keamanan tingkat bank. Transaksi cepat dan terproteksi.', 'from-green-500 to-emerald-500'],
                        ['fas fa-film', 'Film Lengkap', 'Koleksi film terlengkap dari berbagai genre. Update jadwal real-time setiap hari.', 'from-purple-500 to-pink-500'],
                        ['fas fa-map-marker-alt', 'Lokasi Terdekat', 'Temukan bioskop dan jadwal tayang di sekitar Anda dengan mudah dan cepat.', 'from-red-500 to-orange-500'],
                        ['fas fa-gift', 'Promo Eksklusif', 'Dapatkan diskon spesial dan reward untuk pelanggan setia. Hemat lebih banyak!', 'from-yellow-500 to-amber-500'],
                        ['fas fa-headset', 'Dukungan 24/7', 'Tim customer service kami siap membantu kapan pun Anda membutuhkan bantuan.', 'from-indigo-500 to-blue-500'],
                    ];
                @endphp

                @foreach($features as $item)
                    <div class="group bg-white rounded-2xl p-8 border-2 border-gray-100 hover:border-[#007BFF] hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br {{ $item[3] }} flex items-center justify-center text-white text-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="{{ $item[0] }}"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#2C3E50] mb-3">{{ $item[1] }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $item[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Styles and Scripts --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
.swiper-pagination-bullet {
    width: 12px !important;
    height: 12px !important;
    background: white !important;
    opacity: 0.5 !important;
    transition: all 0.3s ease !important;
}

.swiper-pagination-bullet-active {
    opacity: 1 !important;
    width: 32px !important;
    border-radius: 6px !important;
}

.hero-pagination {
    display: flex !important;
    justify-content: center !important;
    gap: 8px !important;
}
</style>

<script>
// Hero Swiper
const heroSwiper = new Swiper('.heroSwiper', {
    loop: true,
    effect: 'fade',
    speed: 1500,
    autoplay: {
        delay: 6000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.hero-pagination',
        clickable: true,
    },
});

// Playing Now Swiper
const playingNowSwiper = new Swiper('.playingNowSwiper', {
    slidesPerView: 1.5,
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: '.swiper-button-next-custom',
        prevEl: '.swiper-button-prev-custom'
    },
    breakpoints: {
        640: {
            slidesPerView: 2.5,
            spaceBetween: 24
        },
        768: {
            slidesPerView: 3.5,
            spaceBetween: 24
        },
        1024: {
            slidesPerView: 4.5,
            spaceBetween: 30
        },
        1280: {
            slidesPerView: 5,
            spaceBetween: 30
        },
    }
});

// Upcoming Swiper
const upcomingSwiper = new Swiper(".upcomingSwiper", {
    slidesPerView: 1.5,
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: ".swiper-button-next-upcoming",
        prevEl: ".swiper-button-prev-upcoming"
    },
    breakpoints: {
        640: {
            slidesPerView: 2.5,
            spaceBetween: 24
        },
        768: {
            slidesPerView: 3.5,
            spaceBetween: 24
        },
        1024: {
            slidesPerView: 4.5,
            spaceBetween: 30
        },
        1280: {
            slidesPerView: 5,
            spaceBetween: 30
        },
    },
});
</script>
@endsection