@extends('layouts.admin')

@section('title', 'Detail Film')
@section('page-title', 'Detail Film')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.films.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Film
        </a>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('admin.films.edit', $film->film_id) }}" 
            class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
            <i class="fas fa-edit mr-2"></i>
            Edit Film
        </a>
        <form action="{{ route('admin.films.destroy', $film->film_id) }}" method="POST" class="inline"
            onsubmit="return confirm('Yakin ingin menghapus film ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                <i class="fas fa-trash mr-2"></i>
                Hapus Film
            </button>
        </form>
    </div>

    <!-- Film Detail Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        
        <!-- Header with Poster -->
        <div class="md:flex">
            <!-- Poster -->
            <div class="md:flex-shrink-0">
                <img src="{{ asset($film->poster_url) }}" alt="{{ $film->judul }}" 
                    class="h-96 w-full md:w-64 object-cover">
            </div>
            
            <!-- Main Info -->
            <div class="p-8 flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $film->judul }}</h1>
                        <div class="flex flex-wrap items-center gap-3 text-sm">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full font-semibold">
                                {{ $film->rating }}
                            </span>
                            <span class="px-3 py-1 rounded-full font-semibold
                                {{ $film->status_tayang == 'Playing Now' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $film->status_tayang == 'Upcoming' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $film->status_tayang == 'Selesai' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $film->status_tayang }}
                            </span>
                            <span class="text-gray-600">
                                <i class="fas fa-film mr-1"></i>{{ $film->genre }}
                            </span>
                            <span class="text-gray-600">
                                <i class="fas fa-clock mr-1"></i>{{ $film->durasi_menit }} menit
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Sinopsis</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $film->sinopsis }}</p>
                </div>

                @if($film->trailer_url)
                <div class="mt-6">
                    <a href="{{ $film->trailer_url }}" target="_blank" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fab fa-youtube mr-2"></i>
                        Tonton Trailer
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Details Section -->
        <div class="border-t border-gray-200 p-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Detail Produksi</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                @if($film->produser)
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Produser</p>
                    <p class="text-base text-gray-900">{{ $film->produser }}</p>
                </div>
                @endif

                @if($film->sutradara)
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Sutradara</p>
                    <p class="text-base text-gray-900">{{ $film->sutradara }}</p>
                </div>
                @endif

                @if($film->penulis)
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Penulis</p>
                    <p class="text-base text-gray-900">{{ $film->penulis }}</p>
                </div>
                @endif

                @if($film->produksi)
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Studio Produksi</p>
                    <p class="text-base text-gray-900">{{ $film->produksi }}</p>
                </div>
                @endif
            </div>

            @if($film->cast_list)
            <div class="mt-6">
                <p class="text-sm font-medium text-gray-500 mb-2">Daftar Pemeran</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $film->cast_list) as $cast)
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                            {{ trim($cast) }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Metadata -->
        <div class="border-t border-gray-200 bg-gray-50 p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sistem</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">ID Film</p>
                    <p class="text-gray-900 font-medium">{{ $film->film_id }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Ditambahkan</p>
                    <p class="text-gray-900 font-medium">{{ $film->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Terakhir Diupdate</p>
                    <p class="text-gray-900 font-medium">{{ $film->updated_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Total Jadwal</p>
                    <p class="text-gray-900 font-medium">{{ $film->jadwals->count() }} jadwal</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Jadwal Tayang Section (Optional) -->
    @if($film->jadwals->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-8 mt-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">Jadwal Tayang</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Studio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($film->jadwals->take(5) as $jadwal)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $jadwal->studio->nama_studio ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $jadwal->status_jadwal == 'Active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $jadwal->status_jadwal == 'Canceled' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $jadwal->status_jadwal == 'Full' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $jadwal->status_jadwal }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($film->jadwals->count() > 5)
        <div class="mt-4 text-center">
            <a href="{{ route('admin.jadwals.index', ['film_id' => $film->film_id]) }}" 
                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat semua jadwal ({{ $film->jadwals->count() }})
            </a>
        </div>
        @endif
    </div>
    @endif

</div>
@endsection