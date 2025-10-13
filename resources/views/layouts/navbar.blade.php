{{-- resources/views/layouts/navbar.blade.php --}}
<nav class="absolute top-0 left-0 w-full z-50 transition-all duration-300" id="navbar">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-[#FEA923]">CINE</span><span class="text-white">VAULT</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <ul class="hidden lg:flex space-x-8 text-white font-medium">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-[#FEA923] transition {{ request()->is('/') ? 'text-[#FEA923]' : '' }}">
                        HOME
                    </a>
                </li>
                <li>
                    <a href="{{ url('/film') }}" class="hover:text-[#FEA923] transition {{ request()->is('film*') ? 'text-[#FEA923]' : '' }}">
                        FILM
                    </a>
                </li>
                <li>
                    <a href="{{ url('/about') }}" class="hover:text-[#FEA923] transition {{ request()->is('about') ? 'text-[#FEA923]' : '' }}">
                        ABOUT
                    </a>
                </li>
                
                @guest
                <li>
                    <a href="{{ route('login') }}" class="hover:text-[#FEA923] transition {{ request()->is('login') ? 'text-[#FEA923]' : '' }}">
                        LOGIN
                    </a>
                </li>
                @endguest
            </ul>

           <form action="{{ route('films.index') }}" method="GET" class="relative">
    <input 
        type="text" 
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari Film (Now Playing / Upcoming)"
        class="px-4 py-2 pr-10 rounded-full bg-white/10 backdrop-blur-sm text-white placeholder-gray-300 border border-white/20 focus:outline-none focus:border-[#FEA923] focus:bg-white/20 transition w-64"
    >
    <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-[#FEA923]">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </button>
</form>


                @guest
                    <!-- Button Buat Akun (Guest) -->
                    <a 
                        href="{{ route('register') }}" 
                        class="bg-[#FEA923] text-black px-6 py-2 rounded-full font-semibold hover:bg-[#e69710] transition shadow-lg hover:shadow-[#FEA923]/50"
                    >
                        Buat Akun
                    </a>
                @else
                    <!-- User Dropdown (Logged In) -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open"
                            class="flex items-center space-x-2 text-white hover:text-[#FEA923] transition"
                        >
                            <div class="w-10 h-10 rounded-full bg-[#FEA923] flex items-center justify-center text-black font-bold">
                                {{ substr(Auth::user()->username, 0, 1) }}
                            </div>
                            <span class="font-semibold">{{ Auth::user()->username }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open"
                            @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-gray-900 rounded-lg shadow-xl border border-gray-800 py-2"
                            style="display: none;"
                        >
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-white hover:bg-gray-800 transition">
                                 Profil Saya
                            </a>
                            <a href="{{ route('profile.riwayat') }}" class="block px-4 py-2 text-white hover:bg-gray-800 transition">
                                 Riwayat Transaksi
                            </a>
                            <hr class="my-2 border-gray-800">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-800 transition">
                                     Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile Menu Button -->
            <button 
                id="mobileMenuBtn" 
                class="lg:hidden text-white focus:outline-none"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden mt-4 pb-4 bg-black/90 backdrop-blur-md rounded-xl p-4">
            <!-- Mobile Search -->
            <div class="mb-4">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Cari Film" 
                        class="w-full px-4 py-2 pr-10 rounded-full bg-white/10 text-white placeholder-gray-300 border border-white/20 focus:outline-none focus:border-[#FEA923]"
                    >
                    <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <ul class="space-y-3 mb-4">
                <li>
                    <a href="{{ route('home') }}" class="block text-white hover:text-[#FEA923] transition py-2">
                        HOME
                    </a>
                </li>
                <li>
                    <a href="{{ url('/film') }}" class="block text-white hover:text-[#FEA923] transition py-2">
                        FILM
                    </a>
                </li>
                <li>
                    <a href="{{ url('/about') }}" class="block text-white hover:text-[#FEA923] transition py-2">
                        ABOUT
                    </a>
                </li>
                
                @guest
                <li>
                    <a href="{{ route('login') }}" class="block text-white hover:text-[#FEA923] transition py-2">
                        LOGIN
                    </a>
                </li>
                @endguest
                
                @auth
                <li>
                    <a href="{{ route('profile.index') }}" class="block text-white hover:text-[#FEA923] transition py-2">
                        👤 Profil Saya
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.riwayat') }}" class="block text-white hover:text-[#FEA923] transition py-2">
                        📜 Riwayat Transaksi
                    </a>
                </li>
                @endauth
            </ul>

            @guest
                <!-- Mobile Button -->
                <a 
                    href="{{ route('register') }}" 
                    class="block text-center bg-[#FEA923] text-black px-6 py-3 rounded-full font-semibold hover:bg-[#e69710] transition"
                >
                    Buat Akun
                </a>
            @else
                <!-- Mobile Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center bg-red-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<!-- Alpine.js for Dropdown -->
<script src="//unpkg.com/alpinejs" defer></script>

<script>
    // Mobile Menu Toggle & Navbar Scroll Effect
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const navbar = document.getElementById('navbar');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }

        if (navbar && window.location.pathname === '/') {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('bg-black/95', 'backdrop-blur-sm', 'border-b', 'border-gray-900');
                    navbar.classList.remove('bg-transparent');
                } else {
                    navbar.classList.remove('bg-black/95', 'backdrop-blur-sm', 'border-b', 'border-gray-900');
                    navbar.classList.add('bg-transparent');
                }
            });
        } else {
            navbar.classList.add('bg-black/95', 'backdrop-blur-sm', 'border-b', 'border-gray-900');
        }
    });
</script>