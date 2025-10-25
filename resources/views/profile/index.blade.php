@extends('layouts.app')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen text-[#2C3E50] pt-28 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        {{-- Menghilangkan 'animate__animated animate__fadeInDown' --}}
        <h1 class="text-4xl md:text-5xl font-extrabold mb-12 text-center text-[#2C3E50]">Profil Pengguna</h1>

        <div class="lg:flex lg:space-x-12">
            <!-- Sidebar Navigation for Tabs -->
            {{-- Menghilangkan 'animate__animated animate__fadeInLeft' --}}
            <div class="lg:w-1/4 bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8 lg:mb-0">
                <nav class="space-y-2">
                    <button id="tab-profile" 
                            class="tab-btn w-full text-left px-5 py-3 rounded-lg font-semibold 
                                   text-[#007BFF] bg-[#EBF7FF] transition-all duration-300 
                                   hover:bg-[#D6EEFF] flex items-center group">
                        <i class="fas fa-user-circle mr-3 text-xl group-hover:scale-110 transition-transform"></i>
                        Informasi Profil
                    </button>
                    <button id="tab-password" 
                            class="tab-btn w-full text-left px-5 py-3 rounded-lg font-semibold 
                                   text-gray-600 hover:text-[#007BFF] hover:bg-gray-50 transition-all duration-300 
                                   flex items-center group">
                        <i class="fas fa-key mr-3 text-xl group-hover:scale-110 transition-transform"></i>
                        Ubah Password
                    </button>
                </nav>
            </div>

            <!-- Content Area for Forms -->
            {{-- Menghilangkan 'animate__animated animate__fadeInRight' --}}
            <div class="lg:w-3/4">
                <!-- Profile Form -->
                <div id="profile-form" class="bg-white rounded-xl p-8 md:p-10 shadow-lg border border-gray-200">
                    <h2 class="text-3xl font-extrabold text-[#2C3E50] mb-8 pb-4 border-b border-gray-200">
                        Detail Informasi
                    </h2>
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                                class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                                       focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                                       transition-all duration-300 placeholder-gray-400"
                                placeholder="Masukkan username Anda">
                        </div>

                        <div>
                            <label for="nama_lengkap" class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                                class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                                       focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                                       transition-all duration-300 placeholder-gray-400"
                                placeholder="Masukkan nama lengkap Anda">
                        </div>

                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                                       focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                                       transition-all duration-300 placeholder-gray-400"
                                placeholder="Masukkan email Anda">
                        </div>

                        <div>
                            <label for="no_telepon" class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                            <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}"
                                class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                                       focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                                       transition-all duration-300 placeholder-gray-400"
                                placeholder="Masukkan nomor telepon Anda">
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-[#007BFF] to-[#0056B3] text-white font-semibold py-3.5 text-base rounded-lg 
                                       hover:opacity-90 transition-opacity duration-300 text-center shadow-md 
                                       flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Form -->
                <div id="password-form" class="hidden bg-white rounded-xl p-8 md:p-10 shadow-lg border border-gray-200">
                    <h2 class="text-3xl font-extrabold text-[#2C3E50] mb-8 pb-4 border-b border-gray-200">
                        Ubah Kata Sandi
                    </h2>
                    <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="current_password" class="block text-gray-700 font-medium mb-2">Password Lama</label>
                            <input type="password" id="current_password" name="current_password"
                                class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                                       focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                                       transition-all duration-300 placeholder-gray-400"
                                placeholder="Masukkan password lama Anda">
                        </div>

                        <div>
                            <label for="new_password" class="block text-gray-700 font-medium mb-2">Password Baru</label>
                            <input type="password" id="new_password" name="new_password"
                                class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                                       focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                                       transition-all duration-300 placeholder-gray-400"
                                placeholder="Masukkan password baru Anda">
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-gray-700 font-medium mb-2">Konfirmasi Password Baru</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                                       focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                                       transition-all duration-300 placeholder-gray-400"
                                placeholder="Konfirmasi password baru Anda">
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-[#007BFF] to-[#0056B3] text-white font-semibold py-3.5 text-base rounded-lg 
                                       hover:opacity-90 transition-opacity duration-300 text-center shadow-md
                                       flex items-center justify-center">
                                <i class="fas fa-key mr-2"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Animate.css link tidak perlu dihapus jika masih digunakan di tempat lain, tapi kelasnya sudah dihapus --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<script>
    const tabProfile = document.getElementById('tab-profile');
    const tabPassword = document.getElementById('tab-password');
    const profileForm = document.getElementById('profile-form');
    const passwordForm = document.getElementById('password-form');

    function setActiveTab(activeTabElement, inactiveTabElement, activeFormElement, inactiveFormElement) {
        activeTabElement.classList.add('text-[#007BFF]', 'bg-[#EBF7FF]');
        activeTabElement.classList.remove('text-gray-600', 'hover:bg-gray-50');
        
        inactiveTabElement.classList.remove('text-[#007BFF]', 'bg-[#EBF7FF]');
        inactiveTabElement.classList.add('text-gray-600', 'hover:bg-gray-50');

        activeFormElement.classList.remove('hidden');
        inactiveFormElement.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        setActiveTab(tabProfile, tabPassword, profileForm, passwordForm);
    });

    tabProfile.addEventListener('click', () => {
        setActiveTab(tabProfile, tabPassword, profileForm, passwordForm);
    });

    tabPassword.addEventListener('click', () => {
        setActiveTab(tabPassword, tabProfile, passwordForm, profileForm);
    });
</script>
@endsection