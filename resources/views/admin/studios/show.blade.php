@extends('layouts.admin')

@section('title', 'Detail Studio')
@section('page-title', 'Detail Studio')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.studios.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Studio
        </a>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('admin.studios.edit', $studio->studio_id) }}" 
            class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
            <i class="fas fa-edit mr-2"></i>
            Edit Studio
        </a>
        <form action="{{ route('admin.studios.destroy', $studio->studio_id) }}" method="POST" class="inline"
            onsubmit="return confirm('Yakin ingin menghapus studio ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                <i class="fas fa-trash mr-2"></i>
                Hapus Studio
            </button>
        </form>
    </div>

    <!-- Studio Header -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg text-white p-8 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">{{ $studio->nama_studio }}</h1>
                <p class="text-blue-100">Studio Bioskop CineVault</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-6">
                <i class="fas fa-door-open text-5xl"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        
        <!-- Kapasitas -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Kapasitas</p>
                    <h3 class="text-3xl font-bold text-blue-600">{{ $studio->kapasitas }}</h3>
                    <p class="text-xs text-gray-500 mt-1">kursi</p>
                </div>
                <i class="fas fa-couch text-blue-500 text-3xl"></i>
            </div>
        </div>

        <!-- Kursi Terdaftar -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Kursi Terdaftar</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ $studio->kursis->count() }}</h3>
                    <p class="text-xs text-gray-500 mt-1">kursi</p>
                </div>
                <i class="fas fa-chair text-green-500 text-3xl"></i>
            </div>
        </div>

        <!-- Total Jadwal -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Jadwal</p>
                    <h3 class="text-3xl font-bold text-purple-600">{{ $studio->jadwals->count() }}</h3>
                    <p class="text-xs text-gray-500 mt-1">jadwal</p>
                </div>
                <i class="fas fa-calendar-alt text-purple-500 text-3xl"></i>
            </div>
        </div>

        <!-- Jadwal Aktif -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Jadwal Aktif</p>
                    <h3 class="text-3xl font-bold text-yellow-600">
                        {{ $studio->jadwals()->where('status_jadwal', 'Active')->count() }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">aktif</p>
                </div>
                <i class="fas fa-play-circle text-yellow-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Layout Kursi -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">Layout Kursi Studio</h3>
        
        <!-- Screen -->
        <div class="text-center mb-8">
            <div class="inline-block bg-gray-800 text-white px-8 py-2 rounded-t-lg">
                <i class="fas fa-tv mr-2"></i>LAYAR
            </div>
        </div>

        <!-- Kursi Layout -->
        <div class="overflow-x-auto">
            @php
                $kursisByBaris = $studio->kursis->groupBy('baris')->sortKeys();
            @endphp

            <div class="flex flex-col items-center space-y-2">
                @foreach($kursisByBaris as $baris => $kursis)
                <div class="flex items-center space-x-2">
                    <!-- Baris Label -->
                    <div class="w-8 text-center font-bold text-gray-700">{{ $baris }}</div>
                    
                    <!-- Kursi -->
                    @foreach($kursis->sortBy('nomor_kursi') as $kursi)
                    <div class="w-10 h-10 bg-green-100 border-2 border-green-500 rounded-t-lg flex items-center justify-center text-xs font-semibold text-green-700 hover:bg-green-200 transition cursor-pointer" 
                         title="{{ $kursi->kode_kursi }}">
                        {{ $kursi->nomor_kursi }}
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex items-center justify-center space-x-6 text-sm">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-green-100 border-2 border-green-500 rounded-t-lg mr-2"></div>
                <span class="text-gray-600">Kursi Tersedia</span>
            </div>
        </div>
    </div>

    <!-- Jadwal Mendatang -->
    @if($upcomingJadwals->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">Jadwal Tayang Mendatang</h3>
        
        <div class="space-y-4">
            @foreach($upcomingJadwals as $jadwal)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 flex-1">
                        <img src="{{ asset($jadwal->film->poster_url) }}" alt="{{ $jadwal->film->judul }}" 
                            class="h-20 w-14 object-cover rounded">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $jadwal->film->judul }}</h4>
                            <div class="flex flex-wrap items-center gap-3 mt-1 text-sm text-gray-600">
                                <span><i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }}</span>
                                <span><i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</span>
                                <span><i class="fas fa-tag mr-1"></i>Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $jadwal->status_jadwal }}
                        </span>
                        <a href="{{ route('admin.jadwals.show', $jadwal->jadwal_id) }}" 
                            class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800">
                            Detail →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($studio->jadwals()->where('tanggal_tayang', '>=', now())->count() > 5)
        <div class="mt-4 text-center">
            <a href="{{ route('admin.jadwals.index', ['studio_id' => $studio->studio_id]) }}" 
                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat semua jadwal ({{ $studio->jadwals()->where('tanggal_tayang', '>=', now())->count() }})
            </a>
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-8 mb-6 text-center">
        <i class="fas fa-calendar-times text-gray-400 text-4xl mb-3"></i>
        <p class="text-gray-600">Belum ada jadwal tayang mendatang untuk studio ini</p>
        <a href="{{ route('admin.jadwals.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
            <i class="fas fa-plus mr-2"></i>
            Tambah Jadwal Baru
        </a>
    </div>
    @endif

    <!-- Metadata -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sistem</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">ID Studio</p>
                <p class="text-gray-900 font-medium">{{ $studio->studio_id }}</p>
            </div>
            <div>
                <p class="text-gray-500">Dibuat Oleh</p>
                <p class="text-gray-900 font-medium">{{ $studio->createdBy->username ?? 'System' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Dibuat Pada</p>
                <p class="text-gray-900 font-medium">{{ $studio->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Terakhir Diupdate</p>
                <p class="text-gray-900 font-medium">{{ $studio->updated_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Total Baris Kursi</p>
                <p class="text-gray-900 font-medium">{{ $studio->kursis->groupBy('baris')->count() }} baris</p>
            </div>
            <div>
                <p class="text-gray-500">Kursi Per Baris</p>
                <p class="text-gray-900 font-medium">~10 kursi</p>
            </div>
        </div>
    </div>

</div>
@endsection