@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    {{-- Navbar Owner --}}
    <nav class="bg-yellow-900 text-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">
                <span class="text-orange-500">CINE</span>VAULT - Owner Panel
            </h1>
            <div class="flex items-center gap-4">
                <span class="text-sm">👤 {{ Auth::user()->nama_lengkap }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 text-sm">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <div class="container mx-auto p-8">
        {{-- Welcome Card --}}
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <h2 class="text-3xl font-bold mb-4">👑 Dashboard Owner</h2>
            <p class="text-gray-600 mb-4">Selamat datang, <strong>{{ Auth::user()->nama_lengkap }}</strong>!</p>
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                <p class="text-yellow-800 text-sm">
                    <strong>Role:</strong> Owner<br>
                    <strong>Email:</strong> {{ Auth::user()->email }}<br>
                    <strong>Akses:</strong> Akses Penuh - Manajemen User, Keuangan, dan Analitik
                </p>
            </div>
        </div>

        {{-- Quick Menu --}}
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-4xl mb-4">👥</div>
                <h3 class="text-xl font-bold mb-2">Kelola User</h3>
                <p class="text-gray-600 text-sm mb-4">Tambah Admin, Kasir</p>
                <button class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 text-sm w-full">
                    Coming Soon
                </button>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-4xl mb-4">💵</div>
                <h3 class="text-xl font-bold mb-2">Keuangan</h3>
                <p class="text-gray-600 text-sm mb-4">Laporan pendapatan</p>
                <button class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 text-sm w-full">
                    Coming Soon
                </button>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-4xl mb-4">📈</div>
                <h3 class="text-xl font-bold mb-2">Analitik</h3>
                <p class="text-gray-600 text-sm mb-4">Statistik bioskop</p>
                <button class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 text-sm w-full">
                    Coming Soon
                </button>
            </div>
        </div>
    </div>
</div>
@endsection