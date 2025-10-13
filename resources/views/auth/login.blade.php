@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black flex items-center justify-center py-20 px-6">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold">
                <span class="text-[#FEA923]">CINE</span><span class="text-white">VAULT</span>
            </h1>
            <p class="text-gray-400 mt-2">Masuk ke akun Anda</p>
        </div>

        <!-- Login Form -->
        <div class="bg-gray-900 rounded-2xl p-8 border border-gray-800">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Nomor Telepon -->
                <div class="mb-6">
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
                        autofocus
                    >
                    @error('no_telepon')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-white font-semibold mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:border-[#FEA923] transition"
                        placeholder="Masukkan password"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-6">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember"
                        class="w-4 h-4 text-[#FEA923] bg-gray-800 border-gray-700 rounded focus:ring-[#FEA923]"
                    >
                    <label for="remember" class="ml-2 text-gray-400 text-sm">
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-[#FEA923] hover:bg-[#e69710] text-black font-bold py-3 rounded-lg transition shadow-lg hover:shadow-xl"
                >
                    Masuk
                </button>

                <!-- Register Link -->
                <p class="text-center text-gray-400 mt-6">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-[#FEA923] hover:underline font-semibold">
                        Daftar Sekarang
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection