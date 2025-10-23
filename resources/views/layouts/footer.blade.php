{{-- resources/views/layouts/footer.blade.php --}}
<footer class="bg-[#1A202C] border-t border-[#2D3748] text-white relative overflow-hidden">
    {{-- Gambar kursi bioskop sebagai background samar --}}
    <div class="absolute inset-0 z-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/black-linen.png');"></div>
    {{-- Gambar ikon kursi bioskop di pojok --}}
    <img src="https://via.placeholder.com/150/66CCFF/FFFFFF?text=Seat+Icon" 
         alt="Cinema Seats Icon" 
         class="absolute bottom-0 right-0 w-32 h-32 md:w-48 md:h-48 opacity-15 rotate-12 transform translate-x-1/4 translate-y-1/4 select-none pointer-events-none z-10"
         style="filter: grayscale(100%) brightness(50%);"
    >
    <img src="https://via.placeholder.com/150/007BFF/FFFFFF?text=Seat+Icon" 
         alt="Cinema Seats Icon" 
         class="absolute top-0 left-0 w-24 h-24 md:w-36 md:h-36 opacity-10 -rotate-12 transform -translate-x-1/4 -translate-y-1/4 select-none pointer-events-none z-10"
         style="filter: grayscale(100%) brightness(50%);"
    >


    <div class="max-w-7xl mx-auto px-6 py-16 relative z-20"> {{-- Meningkatkan padding Y dan z-index --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12"> {{-- Meningkatkan gap --}}
            
            {{-- Kolom 1: Brand & Deskripsi --}}
            <div class="lg:col-span-2">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-5"> {{-- Meningkatkan ukuran & ketebalan font --}}
                    <span class="text-[#66CCFF]">CINE</span><span class="text-white">VAULT</span>
                </h2>
                <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-6 max-w-md">
                    Nikmati pengalaman menonton terbaik dengan pemesanan tiket yang cepat, mudah, dan terpercaya. 
                    Kami hadir untuk membuat setiap momen sinematik Anda lebih berkesan.
                </p>
                <div class="flex space-x-5 mt-6"> {{-- Meningkatkan spacing --}}
                    <a href="https://facebook.com" target="_blank" class="text-gray-400 hover:text-[#007BFF] transition-colors duration-300 text-2xl">
                        <i class="fab fa-facebook-f"></i> {{-- Menggunakan ikon yang lebih modern --}}
                    </a>
                    <a href="https://instagram.com" target="_blank" class="text-gray-400 hover:text-[#007BFF] transition-colors duration-300 text-2xl">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://twitter.com" target="_blank" class="text-gray-400 hover:text-[#007BFF] transition-colors duration-300 text-2xl">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" class="text-gray-400 hover:text-[#007BFF] transition-colors duration-300 text-2xl">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            {{-- Kolom 2: Navigasi --}}
            <div>
                <h3 class="text-xl font-bold mb-5 text-[#66CCFF]">NAVIGASI</h3> {{-- Meningkatkan ukuran & ketebalan font --}}
                <ul class="space-y-3"> {{-- Meningkatkan spacing --}}
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/film') }}" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                            Film
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about') }}" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/login') }}" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/register') }}" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                            Register
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kolom 3: Bantuan & Info --}}
            <div>
                <h3 class="text-xl font-bold mb-5 text-[#66CCFF]">BANTUAN</h3> {{-- Meningkatkan ukuran & ketebalan font --}}
                <ul class="space-y-3"> {{-- Meningkatkan spacing --}}
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                            Cara Pesan Tiket
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                            Syarat & Ketentuan
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                            Kebijakan Privasi
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                            Hubungi Kami
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-[#2D3748] pt-8 mt-8 text-center text-gray-500 text-sm"> {{-- Meningkatkan padding dan warna border --}}
            &copy; {{ date('Y') }} CineVault. All rights reserved. Made with <i class="fas fa-heart text-red-500 mx-1"></i> by YourCompany.
        </div>
    </div>
</footer>