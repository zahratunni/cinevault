{{-- resources/views/layouts/footer.blade.php --}}
<footer class="bg-black border-t border-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            
            {{-- Kolom 1: Brand & Deskripsi --}}
            <div class="lg:col-span-2">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">
                    <span class="text-[#FEA923]">CINE</span><span class="text-white">VAULT</span>
                </h2>
                <p class="text-gray-400 text-sm md:text-base mb-4 max-w-md">
                    Nikmati pengalaman menonton terbaik dengan pemesanan tiket yang cepat dan mudah. 
                    Kami hadir untuk membuat momen nonton Anda lebih berkesan.
                </p>
                <div class="flex space-x-4 mt-4">
                    <a href="https://facebook.com" target="_blank" class="text-gray-400 hover:text-[#FEA923] transition text-xl">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" class="text-gray-400 hover:text-[#FEA923] transition text-xl">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://twitter.com" target="_blank" class="text-gray-400 hover:text-[#FEA923] transition text-xl">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" class="text-gray-400 hover:text-[#FEA923] transition text-xl">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            {{-- Kolom 2: Navigasi --}}
            <div>
                <h3 class="text-lg font-semibold mb-4 text-[#FEA923]">NAVIGASI</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-400 hover:text-white transition text-sm md:text-base">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/film') }}" class="text-gray-400 hover:text-white transition text-sm md:text-base">
                            Film
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about') }}" class="text-gray-400 hover:text-white transition text-sm md:text-base">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/login') }}" class="text-gray-400 hover:text-white transition text-sm md:text-base">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/register') }}" class="text-gray-400 hover:text-white transition text-sm md:text-base">
                            Register
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kolom 3: Bantuan & Info --}}
            <div>
                <h3 class="text-lg font-semibold mb-4 text-[#FEA923]">BANTUAN</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-gray-400 hover:text-white transition text-sm md:text-base">
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-white transition text-sm md:text-base">
                            Cara Pesan Tiket
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-white transition text-sm md:text-base">
                            Syarat & Ketentuan
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-white transition text-sm md:text-base">
                            Kebijakan Privasi
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-white transition text-sm md:text-base">
                            Hubungi Kami
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-gray-800 pt-6 mt-6">