@extends('layouts.admin')

@section('title', 'Kelola Jadwal')
@section('page-title', 'Kelola Jadwal')

@section('content')
<div class="space-y-6">
    
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Jadwal Tayang</h2>
            <p class="text-gray-600 text-sm mt-1">Kelola jadwal tayang film di semua studio</p>
        </div>
        <a href="{{ route('admin.jadwals.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>
            Tambah Jadwal Baru
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.jadwals.index') }}" class="space-y-4">
            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Film</label>
                    <input type="text" name="search" value="{{ $search }}" 
                        placeholder="Cari judul film..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Film Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Film</label>
                    <select name="film_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Film</option>
                        @foreach($films as $film)
                            <option value="{{ $film->film_id }}" {{ $film_id == $film->film_id ? 'selected' : '' }}>
                                {{ $film->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Studio Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Studio</label>
                    <select name="studio_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Studio</option>
                        @foreach($studios as $studio)
                            <option value="{{ $studio->studio_id }}" {{ $studio_id == $studio->studio_id ? 'selected' : '' }}>
                                {{ $studio->nama_studio }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Canceled" {{ $status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                        <option value="Full" {{ $status == 'Full' ? 'selected' : '' }}>Full</option>
                    </select>
                </div>
            </div>

            <!-- Tanggal & Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="md:col-span-3 flex items-end gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.jadwals.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Jadwals List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        
        @if($jadwals->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Film</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Studio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($jadwals as $jadwal)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ asset($jadwal->film->poster_url) }}" alt="{{ $jadwal->film->judul }}" 
                                        class="h-12 w-8 object-cover rounded mr-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $jadwal->film->judul }}</div>
                                        <div class="text-xs text-gray-500">{{ $jadwal->film->durasi_menit }} menit</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $jadwal->studio->nama_studio }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $jadwal->status_jadwal == 'Active' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $jadwal->status_jadwal == 'Canceled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $jadwal->status_jadwal == 'Full' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $jadwal->status_jadwal }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.jadwals.show', $jadwal->jadwal_id) }}" 
                                        class="text-blue-600 hover:text-blue-900" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.jadwals.edit', $jadwal->jadwal_id) }}" 
                                        class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.jadwals.destroy', $jadwal->jadwal_id) }}" 
                                        method="POST" class="inline" 
                                        onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
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
                @foreach($jadwals as $jadwal)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex space-x-4">
                        <img src="{{ asset($jadwal->film->poster_url) }}" alt="{{ $jadwal->film->judul }}" 
                            class="h-24 w-16 object-cover rounded flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-semibold text-gray-900">{{ $jadwal->film->judul }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-door-open mr-1"></i>{{ $jadwal->studio->nama_studio }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-semibold text-blue-600">Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }}</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $jadwal->status_jadwal == 'Active' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $jadwal->status_jadwal == 'Canceled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $jadwal->status_jadwal == 'Full' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $jadwal->status_jadwal }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3 mt-3">
                                <a href="{{ route('admin.jadwals.show', $jadwal->jadwal_id) }}" 
                                    class="text-blue-600 hover:text-blue-900 text-sm">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                <a href="{{ route('admin.jadwals.edit', $jadwal->jadwal_id) }}" 
                                    class="text-yellow-600 hover:text-yellow-900 text-sm">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <form action="{{ route('admin.jadwals.destroy', $jadwal->jadwal_id) }}" 
                                    method="POST" class="inline" 
                                    onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
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
                {{ $jadwals->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-calendar-alt text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada jadwal</h3>
                <p class="text-gray-600 mb-4">Mulai tambahkan jadwal tayang film</p>
                <a href="{{ route('admin.jadwals.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Jadwal Pertama
                </a>
            </div>
        @endif
    </div>

</div>
@endsection