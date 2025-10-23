{{-- resources/views/layouts/navbar.blade.php --}}
<nav class="absolute top-0 left-0 w-full z-50 transition-all duration-300" id="navbar">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-[#66CCFF]">CINE</span><span class="text-white">VAULT</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <ul class="hidden lg:flex space-x-8 text-white font-medium">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-[#66CCFF] transition {{ request()->is('/') ? 'text-[#66CCFF]' : '' }}">
                        HOME
                    </a>
                </li>
                <li>
                    <a href="{{ url('/film') }}" class="hover:text-[#66CCFF] transition {{ request()->is('film*') ? 'text-[#66CCFF]' : '' }}">
                        FILM
                    </a>
                </li>
                <li>
                    <a href="{{ url('/about') }}" class="hover:text-[#66CCFF] transition {{ request()->is('about') ? 'text-[#66CCFF]' : '' }}">
                        ABOUT
                    </a>
                </li>
                
                @guest
                <li>
                    <a href="{{ route('login') }}" class="hover:text-[#66CCFF] transition {{ request()->is('login') ? 'text-[#66CCFF]' : '' }}">
                        LOGIN
                    </a>
                </li>
                @endguest
            </ul>

           <form action="{{ route('films.index') }}" method="GET" class="relative hidden md:block"> {{-- Hidden on small screens --}}
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari Film..."
                    class="px-4 py-2 pr-10 rounded-full bg-white/10 backdrop-blur-sm text-white placeholder-gray-300 border border-white/20 focus:outline-none focus:border-[#66CCFF] focus:bg-white/20 transition w-64"
                >
                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-[#66CCFF]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>

            <div class="hidden lg:flex items-center space-x-4"> {{-- Container untuk Buat Akun / User Dropdown --}}
                @guest
                    <!-- Button Buat Akun (Guest) -->
                    <a 
                        href="{{ route('register') }}" 
                        class="bg-[#007BFF] text-white px-6 py-2 rounded-full font-semibold hover:bg-[#0056B3] transition shadow-lg hover:shadow-[#007BFF]/50"
                    >
                        Buat Akun
                    </a>
                @else
                    <!-- User Dropdown (Logged In) -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open"
                            class="flex items-center space-x-2 text-white hover:text-[#66CCFF] transition-all duration-300 group"
                        >
                            <div class="w-10 h-10 rounded-full bg-[#66CCFF] flex items-center justify-center text-[#1A202C] font-bold text-lg shadow-md group-hover:bg-[#007BFF] transition-all duration-300">
                                {{ substr(Auth::user()->username, 0, 1) }}
                            </div>
                            <span class="font-semibold">{{ Auth::user()->username }}</span>
                            <svg class="w-4 h-4 transform group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-48 bg-[#2D3748] rounded-lg shadow-xl border border-[#1A202C] py-2 overflow-hidden"
                            style="display: none;"
                        >
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-gray-200 hover:bg-[#3C4A5C] hover:text-[#66CCFF] transition-all duration-200">
                                 <i class="fas fa-user mr-2"></i>Profil Saya
                            </a>
                            <a href="{{ route('profile.riwayat') }}" class="block px-4 py-2 text-gray-200 hover:bg-[#3C4A5C] hover:text-[#66CCFF] transition-all duration-200">
                                 <i class="fas fa-history mr-2"></i>Riwayat Transaksi
                            </a>
                            <hr class="my-2 border-[#1A202C]">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-800 hover:text-white transition-all duration-200">
                                     <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile Menu Button -->
            <button 
                id="mobileMenuBtn" 
                class="lg:hidden text-white focus:outline-none z-50"
            >
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden fixed inset-0 bg-[#1A202C]/95 backdrop-blur-lg pt-20 px-6 transform -translate-x-full transition-transform duration-300 ease-in-out">
            <div class="max-w-md mx-auto">
                <!-- Mobile Search -->
                <div class="mb-6">
                    <form action="{{ route('films.index') }}" method="GET" class="relative">
                        <input 
                            type="text" 
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari Film..." 
                            class="w-full px-4 py-3 pr-10 rounded-full bg-white/10 text-white placeholder-gray-300 border border-white/20 focus:outline-none focus:border-[#66CCFF] text-lg"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-[#66CCFF]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Mobile Navigation -->
                <ul class="space-y-4 mb-8 text-lg font-semibold">
                    <li>
                        <a href="{{ route('home') }}" class="block text-gray-200 hover:text-[#66CCFF] transition py-2 {{ request()->is('/') ? 'text-[#66CCFF]' : '' }}">
                            <i class="fas fa-home mr-3 w-6 text-center"></i>HOME
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/film') }}" class="block text-gray-200 hover:text-[#66CCFF] transition py-2 {{ request()->is('film*') ? 'text-[#66CCFF]' : '' }}">
                            <i class="fas fa-film mr-3 w-6 text-center"></i>FILM
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about') }}" class="block text-gray-200 hover:text-[#66CCFF] transition py-2 {{ request()->is('about') ? 'text-[#66CCFF]' : '' }}">
                            <i class="fas fa-info-circle mr-3 w-6 text-center"></i>ABOUT
                        </a>
                    </li>
                    
                    @guest
                    <li class="border-t border-gray-800 pt-4 mt-4">
                        <a href="{{ route('login') }}" class="block text-gray-200 hover:text-[#66CCFF] transition py-2 {{ request()->is('login') ? 'text-[#66CCFF]' : '' }}">
                            <i class="fas fa-sign-in-alt mr-3 w-6 text-center"></i>LOGIN
                        </a>
                    </li>
                    @endguest
                    
                    @auth
                    <li class="border-t border-gray-800 pt-4 mt-4">
                        <a href="{{ route('profile.index') }}" class="block text-gray-200 hover:text-[#66CCFF] transition py-2">
                            <i class="fas fa-user-circle mr-3 w-6 text-center"></i>Profil Saya
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.riwayat') }}" class="block text-gray-200 hover:text-[#66CCFF] transition py-2">
                            <i class="fas fa-receipt mr-3 w-6 text-center"></i>Riwayat Transaksi
                        </a>
                    </li>
                    @endauth
                </ul>

                @guest
                    <!-- Mobile Button -->
                    <a 
                        href="{{ route('register') }}" 
                        class="block text-center bg-[#007BFF] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#0056B3] transition shadow-lg"
                    >
                        Buat Akun
                    </a>
                @else
                    <!-- Mobile Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center bg-red-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-red-700 transition shadow-lg">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @endauth
            </div>
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

        // Toggle mobile menu
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('-translate-x-full'); // Animasi slide
            });
        }

        // Navbar scroll effect
        // Only apply transparent background on home page when at the top
        if (navbar && window.location.pathname === '/' || window.location.pathname === '/home') { // Added /home route
            navbar.classList.add('bg-transparent'); // Default transparent for home
            window.addEventListener('scroll', function() {
                if (window.scrollY > 100) { // Changed scroll threshold for better visibility with hero
                    navbar.classList.add('bg-[#1A202C]/95', 'backdrop-blur-lg', 'border-b', 'border-[#2D3748]');
                    navbar.classList.remove('bg-transparent');
                } else {
                    navbar.classList.remove('bg-[#1A202C]/95', 'backdrop-blur-lg', 'border-b', 'border-[#2D3748]');
                    navbar.classList.add('bg-transparent');
                }
            });
        } else {
            // For other pages, always show dark background
            navbar.classList.add('bg-[#1A202C]/95', 'backdrop-blur-lg', 'border-b', 'border-[#2D3748]');
        }
    });
</script>