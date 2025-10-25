@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F9FAFB] flex items-center justify-center py-20 px-6">
    {{-- Menghilangkan 'animate__animated animate__fadeInUp' --}}
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-10">
            <h1 class="text-5xl font-extrabold leading-tight tracking-tight">
                <span class="text-[#007BFF]">Cine</span><span class="text-[#2C3E50]">Vault</span>
            </h1>
            <p class="text-gray-600 mt-3 text-lg">Daftar akun baru</p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-xl p-8 md:p-10 border border-gray-200 shadow-lg">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama_lengkap" class="block text-gray-700 font-medium mb-2">
                        Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        name="nama_lengkap" 
                        id="nama_lengkap"
                        value="{{ old('nama_lengkap') }}"
                        class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                               focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                               transition-all duration-300 placeholder-gray-400"
                        placeholder="John Doe"
                        required
                        autofocus
                    >
                    @error('nama_lengkap')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-gray-700 font-medium mb-2">
                        Username
                    </label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username"
                        value="{{ old('username') }}"
                        class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                               focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                               transition-all duration-300 placeholder-gray-400"
                        placeholder="johndoe"
                        required
                    >
                    @error('username')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                               focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                               transition-all duration-300 placeholder-gray-400"
                        placeholder="john@example.com"
                        required
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="no_telepon" class="block text-gray-700 font-medium mb-2">
                        Nomor Telepon
                    </label>
                    <input 
                        type="text" 
                        name="no_telepon" 
                        id="no_telepon"
                        value="{{ old('no_telepon') }}"
                        class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                               focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                               transition-all duration-300 placeholder-gray-400"
                        placeholder="08123456789"
                        required
                    >
                    @error('no_telepon')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-gray-700 font-medium mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                               focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                               transition-all duration-300 placeholder-gray-400"
                        placeholder="Minimal 8 karakter"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">
                        Konfirmasi Password
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 
                               focus:outline-none focus:ring-2 focus:ring-[#007BFF] focus:border-transparent 
                               transition-all duration-300 placeholder-gray-400"
                        placeholder="Ketik ulang password"
                        required
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-[#007BFF] to-[#0056B3] text-white font-semibold py-3.5 text-base rounded-lg 
                           hover:opacity-90 transition-opacity duration-300 text-center shadow-md 
                           flex items-center justify-center mt-8"
                >
                    <i class="fas fa-user-plus mr-2"></i> Daftar Akun
                </button>

                <!-- Login Link -->
                <p class="text-center text-gray-600 mt-6 text-base">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-[#007BFF] hover:underline font-semibold transition-colors duration-300">
                        Masuk Sekarang
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
@endsection