@extends('layouts.kasir')

@section('title', 'Dashboard - Kasir')
@section('page-title', 'Dashboard Kasir')

@section('content')
<div class="space-y-6">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Card 1: Transaksi Hari Ini -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Transaksi Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $transaksiHariIni }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-receipt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Belum Dibayar -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Belum Dibayar</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pemesananBelumBayar }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Jadwal Tayang -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Jadwal Tayang Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $jadwalHariIni }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-film text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-sm p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold mb-2">Selamat Datang, {{ $kasir->username }}! 👋</h3>
                <p class="text-blue-100">Anda login sebagai <strong>{{ $kasir->role }}</strong></p>
                <p class="text-blue-100 mt-1">Tanggal: <strong>{{ now()->format('d F Y') }}</strong></p>
            </div>
            <i class="fas fa-clapperboard text-6xl opacity-20"></i>
        </div>
    </div>
</div>

@endsection