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
                <li>
                    <a href="{{ url('login') }}" class="hover:text-[#FEA923] transition {{ request()->is('login') ? 'text-[#FEA923]' : '' }}">
                        LOGIN
                    </a>
                </li>
            </ul>

            <!-- Search + Button (Desktop) -->
            <div class="hidden lg:flex items-center space-x-3">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Cari Film" 
                        class="px-4 py-2 pr-10 rounded-full bg-white/100 backdrop-blur-sm text-white placeholder-gray-300 border border-white/20 focus:outline-none focus:border-[#FEA923] focus:bg-white/20 transition w-48"
                    >
                    <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-[#FEA923]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
                <a 
                    href="{{ url('register') }}" 
                    class="bg-[#FEA923] text-white px-6 py-2 rounded-full font-semibold hover:bg-[#e69710] transition shadow-lg hover:shadow-[#FEA923]/50"
                >
                    Buat Akun
                </a>
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

        <!-- Mobile Menu (Hidden by default) -->
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
                    <a href="{{ route('home') }}" class="block text-white hover:text-[#FEA923] transition py-2 {{ request()->is('/') ? 'text-[#FEA923]' : '' }}">
                        HOME
                    </a>
                </li>
                <li>
                    <a href="{{ url('/film') }}" class="block text-white hover:text-[#FEA923] transition py-2 {{ request()->is('film*') ? 'text-[#FEA923]' : '' }}">
                        FILM
                    </a>
                </li>
                <li>
                    <a href="{{ url('/about') }}" class="block text-white hover:text-[#FEA923] transition py-2 {{ request()->is('about') ? 'text-[#FEA923]' : '' }}">
                        ABOUT
                    </a>
                </li>
                <li>
                    <a href="{{ url('login') }}" class="block text-white hover:text-[#FEA923] transition py-2 {{ request()->is('login') ? 'text-[#FEA923]' : '' }}">
                        LOGIN
                    </a>
                </li>
            </ul>

            <!-- Mobile Button -->
            <a 
                href="{{ url('register') }}" 
                class="block text-center bg-[#FEA923] text-black px-6 py-3 rounded-full font-semibold hover:bg-[#e69710] transition"
            >
                Buat Akun
            </a>
        </div>
    </div>
</nav>

<script>
    // Mobile Menu Toggle & Navbar Scroll Effect
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const navbar = document.getElementById('navbar');

        // Mobile menu toggle
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Navbar scroll effect - only on homepage
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
            // For other pages, always show solid navbar
            navbar.classList.add('bg-black/95', 'backdrop-blur-sm', 'border-b', 'border-gray-900');
        }
    });
</script>