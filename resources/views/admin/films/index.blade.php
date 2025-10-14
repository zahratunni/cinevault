@extends('layouts.admin')

@section('title', 'Kelola Film')
@section('page-title', 'Kelola Film')

@section('content')
<div class="space-y-6">
    
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Film</h2>
            <p class="text-gray-600 text-sm mt-1">Kelola semua film yang tersedia</p>
        </div>
        <a href="{{ route('admin.films.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>
            Tambah Film Baru
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.films.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Film</label>
                <input type="text" name="search" value="{{ $search }}" 
                    placeholder="Cari judul atau genre..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="Playing Now" {{ $status == 'Playing Now' ? 'selected' : '' }}>Playing Now</option>
                    <option value="Upcoming" {{ $status == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="Selesai" {{ $status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="{{ route('admin.films.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Films Grid/List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        
        @if($films->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poster</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Film</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($films as $film)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ asset($film->poster_url) }}" alt="{{ $film->judul }}" 
                                    class="h-16 w-12 object-cover rounded">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $film->judul }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($film->sinopsis, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $film->genre }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $film->durasi_menit }} menit</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $film->rating }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $film->status_tayang == 'Playing Now' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $film->status_tayang == 'Upcoming' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $film->status_tayang == 'Selesai' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $film->status_tayang }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.films.show', $film->film_id) }}" 
                                        class="text-blue-600 hover:text-blue-900" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.films.edit', $film->film_id) }}" 
                                        class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.films.destroy', $film->film_id) }}" 
                                        method="POST" class="inline" 
                                        onsubmit="return confirm('Yakin ingin menghapus film ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden p-4 space-y-4">
                @foreach($films as $film)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex space-x-4">
                        <img src="{{ asset($film->poster_url) }}" alt="{{ $film->judul }}" 
                            class="h-32 w-24 object-cover rounded flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $film->judul }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $film->genre }} • {{ $film->durasi_menit }} menit</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $film->rating }}
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $film->status_tayang == 'Playing Now' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $film->status_tayang == 'Upcoming' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $film->status_tayang == 'Selesai' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $film->status_tayang }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3 mt-3">
                                <a href="{{ route('admin.films.show', $film->film_id) }}" 
                                    class="text-blue-600 hover:text-blue-900 text-sm">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                <a href="{{ route('admin.films.edit', $film->film_id) }}" 
                                    class="text-yellow-600 hover:text-yellow-900 text-sm">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <form action="{{ route('admin.films.destroy', $film->film_id) }}" 
                                    method="POST" class="inline" 
                                    onsubmit="return confirm('Yakin ingin menghapus film ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $films->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-film text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada film</h3>
                <p class="text-gray-600 mb-4">Mulai tambahkan film baru untuk ditampilkan</p>
                <a href="{{ route('admin.films.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Film Pertama
                </a>
            </div>
        @endif
    </div>

</div>
@endsection