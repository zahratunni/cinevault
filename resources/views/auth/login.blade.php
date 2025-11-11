@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F9FAFB] flex items-center justify-center py-20 px-6">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-10">
            <h1 class="text-5xl font-extrabold leading-tight tracking-tight">
                <span class="text-[#007BFF]">Cine</span><span class="text-[#2C3E50]">Vault</span>
            </h1>
            <p class="text-gray-600 mt-3 text-lg">Masuk ke akun Anda</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-xl p-8 md:p-10 border border-gray-200 shadow-lg">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

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
                        autofocus
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
                        placeholder="Masukkan password"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember"
                        class="w-4 h-4 text-[#007BFF] bg-gray-100 border-gray-300 rounded focus:ring-[#007BFF]"
                    >
                    <label for="remember" class="ml-2 text-gray-700 text-sm font-medium">
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-[#007BFF] to-[#0056B3] text-white font-semibold py-3.5 text-base rounded-lg 
                           hover:opacity-90 transition-opacity duration-300 text-center shadow-md 
                           flex items-center justify-center mt-8"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </button>

                <!-- Register Link -->
                <p class="text-center text-gray-600 mt-6 text-base">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-[#007BFF] hover:underline font-semibold transition-colors duration-300">
                        Daftar Sekarang
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
@endsection