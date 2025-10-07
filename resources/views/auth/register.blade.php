@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black flex items-center justify-center py-20 px-6">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold">
                <span class="text-[#FEA923]">CINE</span><span class="text-white">VAULT</span>
            </h1>
            <p class="text-gray-400 mt-2">Daftar akun baru</p>
        </div>

        <!-- Register Form -->
        <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nama Lengkap -->
                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-white font-semibold mb-2">
                        Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        name="nama_lengkap" 
                        id="nama_lengkap"
                        value="{{ old('nama_lengkap') }}"
                        class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:border-[#FEA923] transition"
                        placeholder="John Doe"
                        required
                        autofocus
                    >
                    @error('nama_lengkap')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-white font-semibold mb-2">
                        Username
                    </label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username"
                        value="{{ old('username') }}"
                        class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:border-[#FEA923] transition"
                        placeholder="johndoe"
                        required
                    >
                    @error('username')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-white font-semibold mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:border-[#FEA923] transition"
                        placeholder="john@example.com"
                        required
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div class="mb-4">
                    <label for="no_telepon" class="block text-white font-semibold mb-2">
                        Nomor Telepon
                    </label>
                    <input 
                        type="text" 
                        name="no_telepon" 
                        id="no_telepon"
                        value="{{ old('no_telepon') }}"
                        class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:border-[#FEA923] transition"
                        placeholder="08123456789"
                        required
                    >
                    @error('no_telepon')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-white font-semibold mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:border-[#FEA923] transition"
                        placeholder="Minimal 6 karakter"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-white font-semibold mb-2">
                        Konfirmasi Password
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:border-[#FEA923] transition"
                        placeholder="Ketik ulang password"
                        required
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-[#FEA923] hover:bg-[#e69710] text-black font-bold py-3 rounded-lg transition shadow-lg hover:shadow-xl"
                >
                    Daftar
                </button>

                <!-- Login Link -->
                <p class="text-center text-gray-400 mt-6">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-[#FEA923] hover:underline font-semibold">
                        Masuk Sekarang
                    </a>
                </p>
            </form>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition">
                ← Kembali ke Home
            </a>
        </div>
    </div>
</div>
@endsection