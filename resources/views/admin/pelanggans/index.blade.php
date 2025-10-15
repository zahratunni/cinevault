@extends('layouts.admin')

@section('title', 'Kelola Pelanggan')
@section('page-title', 'Kelola Pelanggan')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pelanggan</h2>
            <p class="text-gray-600 text-sm mt-1">Lihat data dan riwayat transaksi pelanggan</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Pelanggan</p>
                    <h3 class="text-3xl font-bold text-blue-600">{{ $totalCustomers }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Pengguna terdaftar</p>
                </div>
                <i class="fas fa-users text-blue-500 text-4xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pelanggan Aktif</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ $activeCustomers }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Transaksi 3 bulan terakhir</p>
                </div>
                <i class="fas fa-user-check text-green-500 text-4xl"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.pelanggans.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pelanggan</label>
                    <input type="text" name="search" value="{{ $search }}" 
                        placeholder="Cari username, email, atau nama..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="most_active" {{ $sort == 'most_active' ? 'selected' : '' }}>Paling Aktif</option>
                        <option value="top_spender" {{ $sort == 'top_spender' ? 'selected' : '' }}>Top Spender</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="{{ route('admin.pelanggans.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Pelanggan List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        
        @if($pelanggans->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Booking</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pengeluaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pelanggans as $pelanggan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold">
                                            {{ substr($pelanggan->username, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $pelanggan->username }}</div>
                                        <div class="text-sm text-gray-500">{{ $pelanggan->nama_lengkap ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $pelanggan->email }}</div>
                                <div class="text-sm text-gray-500">{{ $pelanggan->no_telepon ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900">{{ $pelanggan->pemesanans_count }}</span>
                                <span class="text-xs text-gray-500"> booking</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-green-600">
                                    Rp {{ number_format($pelanggan->total_spending, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $pelanggan->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.pelanggans.show', $pelanggan->user_id) }}" 
                                    class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden p-4 space-y-4">
                @foreach($pelanggans as $pelanggan)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($pelanggan->username, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-semibold text-gray-900">{{ $pelanggan->username }}</h3>
                            <p class="text-sm text-gray-600">{{ $pelanggan->nama_lengkap ?? '-' }}</p>
                            <div class="mt-2 space-y-1 text-sm text-gray-600">
                                <p><i class="fas fa-envelope w-4 text-gray-400"></i> {{ $pelanggan->email }}</p>
                                @if($pelanggan->no_telepon)
                                <p><i class="fas fa-phone w-4 text-gray-400"></i> {{ $pelanggan->no_telepon }}</p>
                                @endif
                            </div>
                            <div class="flex items-center space-x-4 mt-3 text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-ticket-alt mr-1"></i>{{ $pelanggan->pemesanans_count }} booking
                                </span>
                                <span class="text-green-600 font-semibold">
                                    Rp {{ number_format($pelanggan->total_spending, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.pelanggans.show', $pelanggan->user_id) }}" 
                                    class="text-blue-600 hover:text-blue-900 text-sm">
                                    <i class="fas fa-eye mr-1"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pelanggans->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pelanggan</h3>
                <p class="text-gray-600">Pelanggan akan muncul setelah melakukan registrasi</p>
            </div>
        @endif
    </div>

</div>
@endsection