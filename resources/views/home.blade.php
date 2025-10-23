@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    {{-- Hero Section with Image Slider --}}
    <div class="relative w-full h-[600px] md:h-[750px] overflow-hidden bg-white"> {{-- Added bg-white to the main hero container --}}
        <div class="swiper heroSwiper w-full h-full absolute inset-0 z-0">
            <div class="swiper-wrapper">
                {{-- Slide 1: Custom image with content alignment --}}
                <div class="swiper-slide relative flex items-center justify-center bg-white h-full">
                    {{-- Inner container to align content with max-w-7xl --}}
                    <div class="max-w-7xl mx-auto w-full h-full flex items-center justify-center relative overflow-hidden">
                        <img src="{{ asset('7.jpg') }}" 
                             class="h-full w-auto object-contain" 
                             alt="Movie Banner">
                        {{-- Overlay for text readability --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1A202C] via-transparent to-transparent opacity-80"></div>
                    </div>
                </div>

                {{-- Slide 2: Placeholder background image with content alignment --}}
                <div class="swiper-slide relative flex items-center justify-center bg-white h-full">
                    <div class="max-w-7xl mx-auto w-full h-full flex items-center justify-center relative bg-cover bg-center" 
                         style="background-image: url('https://via.placeholder.com/1920x1080/0056B3/FFFFFF?text=Movie+Banner+2');">
                        {{-- Overlay for text readability --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1A202C] via-transparent to-transparent opacity-80"></div>
                    </div>
                </div>

                {{-- Slide 3: Placeholder background image with content alignment --}}
                <div class="swiper-slide relative flex items-center justify-center bg-white h-full">
                    <div class="max-w-7xl mx-auto w-full h-full flex items-center justify-center relative bg-cover bg-center" 
                         style="background-image: url('https://via.placeholder.com/1920x1080/003F8C/FFFFFF?text=Movie+Banner+3');">
                        {{-- Overlay for text readability --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1A202C] via-transparent to-transparent opacity-80"></div>
                    </div>
                </div>
                {{-- Tambahkan slide lainnya di sini sesuai kebutuhan Anda, dengan struktur inner div yang sama --}}
            </div>
            <!-- Jika Anda ingin pagination atau navigasi di slider hero -->
            <div class="swiper-pagination hero-pagination relative z-10 bottom-8"></div>
        </div>

        {{-- Hero Text Content - positioned absolutely over the slider --}}
        <div class="absolute inset-0 flex items-center justify-center z-20 text-white px-8 text-center">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-5xl md:text-7xl font-extrabold leading-tight tracking-tight mb-4 animate__animated animate__fadeInDown">
                    <span class="text-[#66CCFF]">CineVault:</span> Pengalaman Sinema Terbaik
                </h1>
                <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto animate__animated animate__fadeInUp animate__delay-1s">
                    Nikmati film favoritmu dengan mudah dan cepat.
                </p>
                <a href="#playing-now" class="inline-flex items-center bg-[#66CCFF] text-[#1A202C] font-bold py-4 px-10 rounded-full text-lg 
                                           shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 ease-in-out
                                           animate__animated animate__zoomIn animate__delay-2s">
                    Jelajahi Film <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Playing Now Section --}}
    <div id="playing-now" class="bg-[#F9FAFB] py-24 px-8 border-b border-gray-200">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-[#2C3E50] tracking-tight pb-3 relative">
                    <span class="relative z-10">🎬 Sedang Tayang</span>
                    <span class="absolute bottom-0 left-0 w-24 h-2 bg-[#FFC107] rounded-full -z-0 opacity-70"></span>
                </h2>
                <div class="flex space-x-4 mt-8 md:mt-0">
                    <button class="swiper-button-prev-custom bg-white text-[#007BFF] w-14 h-14 rounded-full flex items-center justify-center 
                                   shadow-md hover:shadow-lg hover:bg-[#007BFF] hover:text-white transition-all duration-300 border border-gray-200">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>
                    <button class="swiper-button-next-custom bg-white text-[#007BFF] w-14 h-14 rounded-full flex items-center justify-center 
                                   shadow-md hover:shadow-lg hover:bg-[#007BFF] hover:text-white transition-all duration-300 border border-gray-200">
                        <i class="fas fa-chevron-right text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="swiper playingNowSwiper mb-16">
                <div class="swiper-wrapper">
                    @forelse($playingNow as $film)
                    <div class="swiper-slide">
                        <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl 
                                    transition-all duration-500 transform hover:-translate-y-2 group border border-gray-100">
                            <div class="aspect-[2/3] relative overflow-hidden">
                                @if($film->poster_url)
                                    <img src="{{ asset($film->poster_url) }}" alt="{{ $film->judul }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center bg-gray-100 text-gray-500">
                                        <p>No Poster</p>
                                    </div>
                                @endif
                                <span class="absolute top-4 left-4 bg-[#007BFF] text-white text-sm font-bold px-4 py-1.5 rounded-full shadow-md">
                                    {{ $film->rating }} <i class="fas fa-star ml-1"></i>
                                </span>
                            </div>
                            <div class="p-5">
                                <h3 class="text-[#2C3E50] font-extrabold text-xl truncate mb-3">{{ $film->judul }}</h3>
                                <div class="flex flex-wrap gap-3 mb-5">
                                    <span class="bg-[#E3F2FD] text-[#007BFF] text-xs px-3 py-1.5 rounded-full font-medium">{{ $film->genre }}</span>
                                    <span class="bg-[#FFFDE7] text-[#FFC107] text-xs px-3 py-1.5 rounded-full font-medium">{{ $film->durasi_menit }} min</span>
                                </div>
                                <a href="{{ route('film.show', $film->film_id) }}" 
                                    class="block w-full bg-gradient-to-r from-[#007BFF] to-[#0056B3] text-white font-semibold py-3.5 text-base rounded-lg 
                                           hover:opacity-90 transition-opacity duration-300 text-center shadow-md">
                                    Beli Tiket <i class="fas fa-ticket-alt ml-2 text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="swiper-slide text-center text-gray-500 py-12 col-span-full">Belum ada film yang sedang tayang</div>
                    @endforelse
                </div>
            </div>

            <div class="text-center mt-20">
                <a href="{{ route('films.playingNow') }}" 
                    class="inline-flex items-center bg-white text-[#007BFF] font-semibold py-4 px-12 rounded-full text-lg 
                           border border-gray-200 shadow-md hover:shadow-lg hover:bg-[#EBF7FF] transition-all duration-300">
                    Lihat Semua Film <i class="fas fa-chevron-right ml-3"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Upcoming Section --}}
    <div class="bg-white py-24 px-8 border-b border-gray-200">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-[#2C3E50] tracking-tight pb-3 relative">
                    <span class="relative z-10">🗓️ Segera Tayang</span>
                    <span class="absolute bottom-0 left-0 w-24 h-2 bg-[#007BFF] rounded-full -z-0 opacity-70"></span>
                </h2>
                <div class="flex space-x-4 mt-8 md:mt-0">
                    <button class="swiper-button-prev-upcoming bg-[#F8F9FA] text-gray-600 w-14 h-14 rounded-full flex items-center justify-center 
                                   shadow-md hover:shadow-lg hover:bg-gray-100 transition-all duration-300 border border-gray-200">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>
                    <button class="swiper-button-next-upcoming bg-[#F8F9FA] text-gray-600 w-14 h-14 rounded-full flex items-center justify-center 
                                   shadow-md hover:shadow-lg hover:bg-gray-100 transition-all duration-300 border border-gray-200">
                        <i class="fas fa-chevron-right text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="swiper upcomingSwiper mb-16">
                <div class="swiper-wrapper">
                    @forelse($upcoming as $film)
                    <div class="swiper-slide">
                        <div class="bg-[#F8F9FA] rounded-xl overflow-hidden shadow-lg hover:shadow-xl 
                                    transition-all duration-500 transform hover:-translate-y-2 group border border-gray-100">
                            <div class="aspect-[2/3] relative overflow-hidden">
                                @if($film->poster_url)
                                    <img src="{{ asset($film->poster_url) }}" alt="{{ $film->judul }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center bg-gray-200 text-gray-500">
                                        <p>No Poster</p>
                                    </div>
                                @endif
                                <span class="absolute top-4 left-4 bg-gray-600 text-white text-sm font-bold px-4 py-1.5 rounded-full shadow-md">
                                    Coming Soon
                                </span>
                            </div>
                            <div class="p-5">
                                <h3 class="text-[#2C3E50] font-extrabold text-xl truncate mb-3">{{ $film->judul }}</h3>
                                <div class="flex flex-wrap gap-3 mb-5">
                                    <span class="bg-gray-200 text-gray-700 text-xs px-3 py-1.5 rounded-full font-medium">{{ $film->genre }}</span>
                                    <span class="bg-gray-200 text-gray-700 text-xs px-3 py-1.5 rounded-full font-medium">{{ $film->durasi_menit }} min</span>
                                </div>
                                <a href="{{ route('film.show', $film->film_id) }}" 
                                    class="block w-full bg-gray-200 text-gray-700 font-semibold py-3.5 text-base rounded-lg 
                                           hover:bg-gray-300 transition-colors duration-300 text-center shadow-sm">
                                    Detail & Trailer <i class="fas fa-info-circle ml-2 text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="swiper-slide text-center text-gray-500 py-12 col-span-full">Belum ada film upcoming</div>
                    @endforelse
                </div>
            </div>

            <div class="text-center mt-20">
                <a href="{{ route('films.upcoming') }}" 
                    class="inline-flex items-center bg-[#F8F9FA] text-gray-600 font-semibold py-4 px-12 rounded-full text-lg 
                           border border-gray-200 shadow-md hover:shadow-lg hover:bg-gray-100 transition-all duration-300">
                    Lihat Kalender Rilis <i class="fas fa-calendar-alt ml-3"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Why Choose Section --}}
    <div class="bg-[#F9FAFB] py-24 px-8">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-20 text-[#2C3E50]">
                Kenapa Memilih <span class="text-[#007BFF]">CineVault</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @php
                    $features = [
                        ['<i class="fas fa-ticket-alt"></i>', 'Booking Instan', 'Pesan tiketmu kapan saja, tanpa antrian, konfirmasi langsung di aplikasi.'],
                        ['<i class="fas fa-credit-card"></i>', 'Pembayaran Aman', 'Pilih metode pembayaran yang kamu sukai, cepat & terproteksi.'],
                        ['<i class="fas fa-film"></i>', 'Film Lengkap & Update', 'Selalu ada film baru dan jadwal terakurat setiap jamnya.'],
                        ['<i class="fas fa-map-marker-alt"></i>', 'Lokasi Bioskop Terdekat', 'Temukan bioskop dan film yang tayang di kotamu secara real-time.'],
                        ['<i class="fas fa-star"></i>', 'Loyalitas & Promo', 'Nikmati diskon eksklusif dan program loyalitas pelanggan setia.'],
                        ['<i class="fas fa-headset"></i>', 'Dukungan 24/7', 'Layanan pelanggan kami siap membantu kapan pun Anda butuh.'],
                    ];
                @endphp

                @foreach($features as $item)
                    <div class="bg-white rounded-xl p-10 border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="text-[#007BFF] text-5xl mb-6 mx-auto w-20 h-20 flex items-center justify-center rounded-full bg-[#EBF7FF] shadow-inner">
                            {!! $item[0] !!}
                        </div>
                        <h3 class="text-[#2C3E50] text-xl font-extrabold mb-3">{{ $item[1] }}</h3>
                        <p class="text-[#607D8B] text-sm font-light leading-relaxed">{{ $item[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
{{-- Animate.css untuk animasi teks di hero section --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
// Swiper configuration for Hero Section
const heroSwiper = new Swiper('.heroSwiper', {
    loop: true,
    effect: 'fade', // Memberikan efek transisi yang lebih halus untuk gambar
    speed: 1000, // Kecepatan transisi slide
    autoplay: {
        delay: 5000, // Ganti slide setiap 5 detik
        disableOnInteraction: false, // Lanjutkan autoplay setelah user interaksi
    },
    pagination: {
        el: '.hero-pagination',
        clickable: true,
    },
});

// Swiper configuration for Playing Now
const playingNowSwiper = new Swiper('.playingNowSwiper', {
    slidesPerView: 2,
    spaceBetween: 25, 
    loop: true, 
    centeredSlides: false,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    navigation: { 
        nextEl: '.swiper-button-next-custom', 
        prevEl: '.swiper-button-prev-custom' 
    },
    breakpoints: {
        640: { 
            slidesPerView: 3, 
            spaceBetween: 25 
        },
        768: { 
            slidesPerView: 3, 
            spaceBetween: 30 
        },
        1024: { 
            slidesPerView: 4, 
            spaceBetween: 30 
        },
        1280: { 
            slidesPerView: 5, 
            spaceBetween: 30 
        },
    }
});

// Swiper configuration for Upcoming
const upcomingSwiper = new Swiper(".upcomingSwiper", {
    slidesPerView: 2,
    spaceBetween: 25,
    loop: true,
    centeredSlides: false,
    autoplay: {
        delay: 6000, 
        disableOnInteraction: false,
    },
    navigation: { 
        nextEl: ".swiper-button-next-upcoming", 
        prevEl: ".swiper-button-prev-upcoming" 
    },
    breakpoints: {
        640: { 
            slidesPerView: 3, 
            spaceBetween: 25 
        },
        768: { 
            slidesPerView: 4, 
            spaceBetween: 30 
        },
        1024: { 
            slidesPerView: 5, 
            spaceBetween: 30 
        },
    },
});
</script>
@endsection