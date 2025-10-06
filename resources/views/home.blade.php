@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen">
    
    <!-- Hero Section with Background Image -->
    <div class="relative h-screen min-h-[600px]">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('bg.jpg.jpg');">
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
        </div>
        
        <!-- Hero Content - Positioned Bottom Left -->
        <div class="relative z-10 h-full flex items-end">
            <div class="max-w-7xl mx-auto px-6 pb-16 md:pb-20 lg:pb-24 w-full">
                <div class="max-w-2xl">
                   <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4">
                        <span class="text-[#FEA923]">CINE</span>VAULT
                    </h1>

                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-6">
                        Kunci pengalaman<br>
                        <span class="text-[#FEA923]">menonton</span> terbaikmu
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Playing Now Section with Swiper -->
    <div class="bg-black py-16 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl md:text-4xl font-bold text-white">Playing Now</h2>
                <div class="flex space-x-2">
                    <button class="swiper-button-prev-custom bg-[#FEA923] hover:bg-[#e69710] text-black w-10 h-10 rounded-full flex items-center justify-center transition font-bold">
                        ←
                    </button>
                    <button class="swiper-button-next-custom bg-[#FEA923] hover:bg-[#e69710] text-black w-10 h-10 rounded-full flex items-center justify-center transition font-bold">
                        →
                    </button>
                </div>
            </div>

            <!-- Swiper Container -->
            <div class="swiper playingNowSwiper mb-10">
                <div class="swiper-wrapper">
                    @forelse($playingNow as $film)
                    <div class="swiper-slide">
                        <div class="bg-[#1D1B1B] rounded-xl overflow-hidden border border-gray-800 hover:border-[#FEA923] transition group h-full">
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
                                    <span class=" text-white text-medium px-2 py-1 whitespace-nowrap">{{ $film->rating }}</span>

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
                    </div>
                    @empty
                    <div class="swiper-slide">
                        <div class="bg-gradient-to-b from-gray-900 to-black rounded-xl overflow-hidden border border-gray-800 p-8 text-center">
                            <p class="text-gray-400">Belum ada film yang sedang tayang</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('films.playingNow') }}" 
   class="inline-block bg-transparent border-2 border-white hover:border-[#FEA923] hover:bg-[#FEA923] text-white hover:text-black font-semibold py-3 px-8 rounded-full transition">
   Lihat Semua
</a>

            </div>
        </div>
    </div>

    <!-- Up Coming Section -->
<div class="bg-black py-16 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white">Up Coming</h2>
            <div class="flex space-x-2">
                <button class="swiper-button-prev-upcoming bg-[#FEA923] hover:bg-[#e69710] text-black w-10 h-10 rounded-full flex items-center justify-center transition font-bold">
                    ←
                </button>
                <button class="swiper-button-next-upcoming bg-[#FEA923] hover:bg-[#e69710] text-black w-10 h-10 rounded-full flex items-center justify-center transition font-bold">
                    →
                </button>
            </div>
        </div>

        <!-- Swiper Container -->
        <div class="swiper upcomingSwiper mb-10">
            <div class="swiper-wrapper">
                @forelse($upcoming as $film)
                <div class="swiper-slide">
                    <div class="bg-[#1D1B1B] rounded-xl overflow-hidden border border-gray-800 hover:border-[#FEA923] transition group h-full">
                        
                        <!-- Poster -->
                        <div class="aspect-[2/3] bg-gray-800 relative overflow-hidden">
                            @if($film->poster_url)
                                <img src="{{ asset($film->poster_url) }}" 
                                     alt="{{ $film->judul }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-gray-600">
                                    <div class="text-center">
                                        <div class="text-4xl mb-2">🎥</div>
                                        <p class="text-sm">No Poster</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Detail Film -->
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-white font-bold text-lg truncate">{{ $film->judul }}</h3>
                                <span class=" text-white text-semibold px-2 py-1 rounded whitespace-nowrap">{{ $film->rating }}</span>
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
                </div>
                @empty
                <div class="swiper-slide">
                    <div class="bg-gradient-to-b from-gray-900 to-black rounded-xl overflow-hidden border border-gray-800 p-8 text-center">
                        <p class="text-gray-400">Belum ada film upcoming</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <div class="text-center">
           <a href="{{ route('films.upcoming') }}" 
                class="inline-block bg-transparent border-2 border-white hover:border-[#FEA923] hover:bg-[#FEA923] text-white hover:text-black font-semibold py-3 px-8 rounded-full transition">
                 Lihat Semua
            </a>

        </div>
    </div>
</div>


    <!-- Why Choose CINEVAULT Section -->
    <div class="bg-black py-16 px-6">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">
                <span class="text-white">KENAPA MEMILIH </span>
                <span class="text-[#FEA923]">CINEVAULT</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-gradient-to-br from-gray-900 to-black border border-[#FEA923] rounded-2xl p-6 hover:shadow-lg hover:shadow-[#FEA923]/20 transition">
                    <div class="text-[#FEA923] text-4xl mb-4">🎥</div>
                    <h3 class="text-white text-xl font-bold mb-3">Film Lengkap & Update</h3>
                    <p class="text-gray-400 text-sm">
                        Dari Now Playing hingga Upcoming, selalu ada film baru untuk ditonton.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-gradient-to-br from-gray-900 to-black border border-[#FEA923] rounded-2xl p-6 hover:shadow-lg hover:shadow-[#FEA923]/20 transition">
                    <div class="text-[#FEA923] text-4xl mb-4">💰</div>
                    <h3 class="text-white text-xl font-bold mb-3">Harga Transparan</h3>
                    <p class="text-gray-400 text-sm">
                        Nikmati tiket dengan harga jujur, weekday & weekend berbeda secara otomatis.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-gradient-to-br from-gray-900 to-black border border-[#FEA923] rounded-2xl p-6 hover:shadow-lg hover:shadow-[#FEA923]/20 transition">
                    <div class="text-[#FEA923] text-4xl mb-4">🍿</div>
                    <h3 class="text-white text-xl font-bold mb-3">Nyaman & Seru</h3>
                    <p class="text-gray-400 text-sm">
                        Layar lebar, suara jernih, dan kursi empuk bikin nonton makin asik.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="bg-gradient-to-br from-gray-900 to-black border border-[#FEA923] rounded-2xl p-6 hover:shadow-lg hover:shadow-[#FEA923]/20 transition">
                    <div class="text-[#FEA923] text-4xl mb-4">💳</div>
                    <h3 class="text-white text-xl font-bold mb-3">Bayar Aman & Mudah</h3>
                    <p class="text-gray-400 text-sm">
                        Pilih metode pembayaran favoritmu dengan proses cepat dan terjamin.
                    </p>
                </div>

                <!-- Card 5 -->
                <div class="bg-gradient-to-br from-gray-900 to-black border border-[#FEA923] rounded-2xl p-6 hover:shadow-lg hover:shadow-[#FEA923]/20 transition">
                    <div class="text-[#FEA923] text-4xl mb-4">🎬</div>
                    <h3 class="text-white text-xl font-bold mb-3">Booking tiket film favoritmu</h3>
                    <p class="text-gray-400 text-sm">
                        Cukup dalam beberapa klik, tanpa antri, tanpa ribet.
                    </p>
                </div>

                <!-- Card 6 -->
                <div class="bg-gradient-to-br from-gray-900 to-black border border-[#FEA923] rounded-2xl p-6 hover:shadow-lg hover:shadow-[#FEA923]/20 transition">
                    <div class="text-[#FEA923] text-4xl mb-4">🎟️</div>
                    <h3 class="text-white text-xl font-bold mb-3">Promo & Diskon Spesial</h3>
                    <p class="text-gray-400 text-sm">
                        Jangan lewatkan promo menarik dan harga hemat untuk nonton bareng teman.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Swiper JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    // Initialize Swiper for Playing Now
    const swiper = new Swiper('.playingNowSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next-custom',
            prevEl: '.swiper-button-prev-custom',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 24,
            },
            1280: {
                slidesPerView: 5,
                spaceBetween: 24,
            }
        }
    });

    var upcomingSwiper = new Swiper(".upcomingSwiper", {
    slidesPerView: 2,
    spaceBetween: 20,
    breakpoints: {
        640: { slidesPerView: 2 },
        768: { slidesPerView: 3 },
        1024: { slidesPerView: 5 },
    },
    navigation: {
        nextEl: ".swiper-button-next-upcoming",
        prevEl: ".swiper-button-prev-upcoming",
    },
});

</script>
@endsection