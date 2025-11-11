{{-- resources/views/layouts/navbar.blade.php --}}
<nav class="absolute top-0 left-0 w-full z-50 transition-all duration-300 bg-[#1A202C] shadow-lg font-sans" id="navbar"> {{-- Tetap bg-[#1A202C] --}}
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-black"> {{-- Ubah font-extrabold menjadi font-black --}}
                    <span class="text-[#66CCFF]">CINE</span><span class="text-white">VAULT</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <ul class="hidden lg:flex space-x-8 text-white font-semibold items-center">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-[#66CCFF] transition {{ request()->is('/') ? 'text-[#66CCFF]' : '' }}">
                        HOME
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}#playing-now" class="hover:text-[#66CCFF] transition {{ request()->is('film*') ? 'text-[#66CCFF]' : '' }}">
                        FILM
                    </a>
                </li>

                @auth
                <li>
                    <a href="{{ route('profile.index') }}" class="hover:text-[#66CCFF] transition {{ request()->is('profile') ? 'text-[#66CCFF]' : '' }}">
                        PROFILE
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.riwayat') }}" class="hover:text-[#66CCFF] transition {{ request()->is('profile/riwayat') ? 'text-[#66CCFF]' : '' }}">
                        RIWAYAT
                    </a>
                </li>
                @endauth
            </ul>

            <!-- AUTH / GUEST Buttons (Desktop) -->
            <div class="hidden lg:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}"
                       class="bg-[#007BFF] text-white px-6 py-2 rounded-full font-semibold hover:bg-[#0056B3] transition shadow-lg hover:shadow-xl"> 
                        LOGIN
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-[#007BFF] text-white px-6 py-2 rounded-full font-semibold hover:bg-[#0056B3] transition shadow-lg hover:shadow-xl"> 
                        REGISTER
                    </a>
                @else
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="bg-[#007BFF] text-white px-6 py-2 rounded-full font-semibold hover:bg-[#0056B3] transition shadow-lg hover:shadow-xl">
                            LOGOUT
                        </button>
                    </form>
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
        <div id="mobileMenu" class="hidden lg:hidden fixed inset-0 bg-[#1A202C]/95 backdrop-blur-lg pt-20 px-6 transform -translate-x-full transition-transform 
        duration-300 ease-in-out">
            <div class="max-w-md mx-auto">
                <!-- Mobile Navigation -->
                <ul class="space-y-4 mb-8 text-lg font-semibold">
                    <li>
                        <a href="{{ route('home') }}" class="block text-gray-200 hover:text-[#66CCFF] transition py-2 {{ request()->is('/') 
                        ? 'text-[#66CCFF]' : '' }}">
                            <i class="fas fa-home mr-3 w-6 text-center"></i>HOME
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#playing-now" class="block text-gray-200 hover:text-[#66CCFF] transition py-2 {{ request()->is('film*') 
                        ? 'text-[#66CCFF]' : '' }}">
                            <i class="fas fa-film mr-3 w-6 text-center"></i>FILM
                        </a>
                    </li>

                    @auth
                    <li>
                        <a href="{{ route('profile.index') }}" class="block text-gray-200 hover:text-[#66CCFF] transition py-2 {{ request()->is('profile') 
                        ? 'text-[#66CCFF]' : '' }}">
                            <i class="fas fa-user-circle mr-3 w-6 text-center"></i>PROFILE
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.riwayat') }}" class="block text-gray-200 hover:text-[#66CCFF] transition py-2 {{ request()->is('profile/riwayat') 
                        ? 'text-[#66CCFF]' : '' }}">
                            <i class="fas fa-receipt mr-3 w-6 text-center"></i>RIWAYAT
                        </a>
                    </li>
                    @endauth

                    <li class="border-t border-gray-800 pt-4 mt-4 flex flex-col space-y-3">
                        @guest
                            <a href="{{ route('login') }}" class="block text-center bg-[#007BFF] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#0056B3] 
                            transition shadow-lg"> 
                                <i class="fas fa-sign-in-alt mr-2"></i>LOGIN
                            </a>
                            <a href="{{ route('register') }}" class="block text-center bg-[#007BFF] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#0056B3]
                             transition shadow-lg">
                                <i class="fas fa-user-plus mr-2"></i>REGISTER
                            </a>
                        @else
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-center bg-[#007BFF] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#0056B3] 
                                transition shadow-lg"> 
                                    <i class="fas fa-sign-out-alt mr-2"></i>LOGOUT
                                </button>
                            </form>
                        @endguest
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

{{-- Alpine.js not needed anymore for dropdown in navbar, but kept for other x-data usage --}}
<script src="//unpkg.com/alpinejs" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const navbar = document.getElementById('navbar');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('-translate-x-full');
                const icon = mobileMenuBtn.querySelector('svg');
                if (!mobileMenu.classList.contains('hidden')) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
                } else {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
                }
            });
        }

        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('border-b', 'border-[#2D3748]');
            } else {
                navbar.classList.remove('border-b', 'border-[#2D3748]');
            }
        });

        // Smooth scroll untuk link anchor (seperti FILM ke #playing-now)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                // Tutup mobile menu jika terbuka
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    mobileMenu.classList.add('-translate-x-full');
                    mobileMenuBtn.querySelector('svg').innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
                }

                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Logika untuk link FILM agar kembali ke home dan scroll ke #playing-now jika di halaman lain
        document.querySelectorAll('a[href="{{ route('home') }}#playing-now"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const currentPath = window.location.pathname;
                const homePath = "{{ route('home') }}";

                const isHomePage = currentPath === homePath || (homePath === '/' && currentPath === '/home');

                if (!isHomePage) {
                    e.preventDefault();
                    window.location.href = this.href;
                }
            });
        });
    });
</script>