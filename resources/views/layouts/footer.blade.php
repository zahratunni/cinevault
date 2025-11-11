{{-- resources/views/layouts/footer.blade.php --}}
<footer class="bg-[#1A202C] border-t border-[#2D3748] text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 py-16 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            {{-- Kolom 1: Brand & Deskripsi --}}
            <div class="lg:col-span-2">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-5">
                    <span class="text-[#66CCFF]">CINE</span><span class="text-white">VAULT</span>
                </h2>
                <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-6 max-w-md">
                    Nikmati pengalaman menonton terbaik dengan pemesanan tiket yang cepat, mudah, dan terpercaya. 
                    Kami hadir untuk membuat setiap momen sinematik Anda lebih berkesan.
                </p>
                <div class="flex space-x-5 mt-6">
                    <a href="https://facebook.com" target="_blank" class="text-gray-400 hover:text-[#007BFF] transition-colors duration-300 text-2xl">
                        <i class="fab fa-facebook-f"></i>
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
                <h3 class="text-xl font-bold mb-5 text-[#66CCFF]">NAVIGASI</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-300 text-base flex items-center group">
                            <i class="fas fa-home mr-2 text-[#66CCFF] group-hover:text-white transition-colors"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#playing-now" class="text-gray-300 hover:text-white transition-colors duration-300 text-base flex items-center group">
                            <i class="fas fa-film mr-2 text-[#66CCFF] group-hover:text-white transition-colors"></i>
                            Film
                        </a>
                    </li>
                    @auth
                    <li>
                        <a href="{{ route('profile.index') }}" class="text-gray-300 hover:text-white transition-colors duration-300 text-base flex items-center group">
                            <i class="fas fa-user-circle mr-2 text-[#66CCFF] group-hover:text-white transition-colors"></i>
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.riwayat') }}" class="text-gray-300 hover:text-white transition-colors duration-300 text-base flex items-center group">
                            <i class="fas fa-receipt mr-2 text-[#66CCFF] group-hover:text-white transition-colors"></i>
                            Riwayat
                        </a>
                    </li>
                    @endauth
                    @guest
                    <li>
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-300 text-base flex items-center group">
                            <i class="fas fa-sign-in-alt mr-2 text-[#66CCFF] group-hover:text-white transition-colors"></i>
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition-colors duration-300 text-base flex items-center group">
                            <i class="fas fa-user-plus mr-2 text-[#66CCFF] group-hover:text-white transition-colors"></i>
                            Register
                        </a>
                    </li>
                    @endguest
                </ul>
            </div>

            {{-- Kolom 3: Kontak Kami --}}
            <div>
                <h3 class="text-xl font-bold mb-5 text-[#66CCFF]">KONTAK KAMI</h3>
                <ul class="space-y-3">
                    <li class="flex items-start group">
                        <i class="fas fa-envelope mr-3 text-[#66CCFF] mt-1"></i>
                        <div>
                            <p class="text-gray-400 text-xs mb-1">Email</p>
                            <a href="mailto:info@cinevault.com" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                                info@cinevault.com
                            </a>
                        </div>
                    </li>
                    <li class="flex items-start group">
                        <i class="fas fa-phone mr-3 text-[#66CCFF] mt-1"></i>
                        <div>
                            <p class="text-gray-400 text-xs mb-1">Telepon</p>
                            <a href="tel:+622112345678" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                                (021) 1234-5678
                            </a>
                        </div>
                    </li>
                    <li class="flex items-start group">
                        <i class="fab fa-whatsapp mr-3 text-[#66CCFF] mt-1"></i>
                        <div>
                            <p class="text-gray-400 text-xs mb-1">WhatsApp</p>
                            <a href="https://wa.me/628123456789" target="_blank" class="text-gray-300 hover:text-white transition-colors duration-300 text-base">
                                +62 812-3456-7890
                            </a>
                        </div>
                    </li>
                    <li class="flex items-start group">
                        <i class="fas fa-map-marker-alt mr-3 text-[#66CCFF] mt-1"></i>
                        <div>
                            <p class="text-gray-400 text-xs mb-1">Alamat</p>
                            <p class="text-gray-300 text-base leading-relaxed">
                                Jakarta, Indonesia
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-[#2D3748] pt-8 mt-8 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} CineVault. All rights reserved. Made with <i class="fas fa-heart text-red-500 mx-1"></i> by YourCompany.
        </div>
    </div>
</footer>