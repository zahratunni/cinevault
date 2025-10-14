@extends('layouts.admin')

@section('title', 'Kelola Studio')
@section('page-title', 'Kelola Studio')

@section('content')
<div class="space-y-6">
    
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Studio</h2>
            <p class="text-gray-600 text-sm mt-1">Kelola ruangan bioskop dan kapasitas kursi</p>
        </div>
        <a href="{{ route('admin.studios.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>
            Tambah Studio Baru
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.studios.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" 
                    placeholder="Cari nama studio..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            <a href="{{ route('admin.studios.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </form>
    </div>

    <!-- Studios Grid -->
    @if($studios->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($studios as $studio)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $studio->nama_studio }}</h3>
                        <p class="text-blue-100 text-sm mt-1">Studio Bioskop</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-door-open text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-blue-600">{{ $studio->kapasitas }}</p>
                        <p class="text-xs text-gray-600 mt-1">Kursi Tersedia</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">{{ $studio->jadwals_count }}</p>
                        <p class="text-xs text-gray-600 mt-1">Total Jadwal</p>
                    </div>
                </div>

                <!-- Info -->
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-chair w-5 text-gray-400"></i>
                        <span>{{ $studio->kursis_count }} kursi terdaftar</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                        <span>{{ $studio->jadwals_count }} jadwal tayang</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 pt-4 border-t">
                    <a href="{{ route('admin.studios.show', $studio->studio_id) }}" 
                        class="flex-1 text-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </a>
                    <a href="{{ route('admin.studios.edit', $studio->studio_id) }}" 
                        class="flex-1 text-center px-4 py-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form action="{{ route('admin.studios.destroy', $studio->studio_id) }}" 
                        method="POST" class="flex-1" 
                        onsubmit="return confirm('Yakin ingin menghapus studio ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="bg-white rounded-lg shadow p-4">
        {{ $studios->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow text-center py-12">
        <i class="fas fa-door-open text-gray-400 text-6xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada studio</h3>
        <p class="text-gray-600 mb-4">Mulai tambahkan studio/ruangan bioskop</p>
        <a href="{{ route('admin.studios.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>
            Tambah Studio Pertama
        </a>
    </div>
    @endif

</div>
@endsection