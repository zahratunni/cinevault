@extends('layouts.admin')

@section('title', 'Detail Jadwal')
@section('page-title', 'Detail Jadwal')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.jadwals.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Jadwal
        </a>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('admin.jadwals.edit', $jadwal->jadwal_id) }}" 
            class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
            <i class="fas fa-edit mr-2"></i>
            Edit Jadwal
        </a>
        <form action="{{ route('admin.jadwals.destroy', $jadwal->jadwal_id) }}" method="POST" class="inline"
            onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                <i class="fas fa-trash mr-2"></i>
                Hapus Jadwal
            </button>
        </form>
    </div>

    <!-- Jadwal Detail Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        
        <!-- Header with Film Info -->
        <div class="md:flex">
            <!-- Poster -->
            <div class="md:flex-shrink-0">
                <img src="{{ asset($jadwal->film->poster_url) }}" alt="{{ $jadwal->film->judul }}" 
                    class="h-96 w-full md:w-64 object-cover">
            </div>
            
            <!-- Main Info -->
            <div class="p-8 flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $jadwal->film->judul }}</h1>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Studio</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $jadwal->studio->nama_studio }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kapasitas</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $jadwal->studio->kapasitas }} kursi</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Tayang</p>
                        <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Waktu</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Harga Tiket</p>
                        <p class="text-lg font-semibold text-blue-600">Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $jadwal->status_jadwal == 'Active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $jadwal->status_jadwal == 'Canceled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $jadwal->status_jadwal == 'Full' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ $jadwal->status_jadwal }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Genre</p>
                        <p class="text-gray-900">{{ $jadwal->film->genre }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Rating</p>
                        <p class="text-gray-900">{{ $jadwal->film->rating }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Durasi</p>
                        <p class="text-gray-900">{{ $jadwal->film->durasi_menit }} menit</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status Film</p>
                        <p class="text-gray-900">{{ $jadwal->film->status_tayang }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="border-t border-gray-200 p-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Statistik Pemesanan</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Total Bookings -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600 mb-1">Total Pemesanan</p>
                            <h3 class="text-2xl font-bold text-blue-900">{{ $jadwal->pemesanans->count() }}</h3>
                        </div>
                        <i class="fas fa-ticket-alt text-blue-500 text-2xl"></i>
                    </div>
                </div>

                <!-- Kursi Terpesan -->
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600 mb-1">Kursi Terpesan</p>
                            <h3 class="text-2xl font-bold text-green-900">
                                {{ $jadwal->pemesanans()->whereIn('status_pemesanan', ['Lunas', 'Menunggu Bayar'])->count() }}
                            </h3>
                        </div>
                        <i class="fas fa-chair text-green-500 text-2xl"></i>
                    </div>
                </div>

                <!-- Kursi Tersisa -->
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-yellow-600 mb-1">Kursi Tersisa</p>
                            <h3 class="text-2xl font-bold text-yellow-900">
                                {{ $jadwal->studio->kapasitas - $jadwal->pemesanans()->whereIn('status_pemesanan', ['Lunas', 'Menunggu Bayar'])->count() }}
                            </h3>
                        </div>
                        <i class="fas fa-couch text-yellow-500 text-2xl"></i>
                    </div>
                </div>

                <!-- Total Pendapatan -->
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-600 mb-1">Pendapatan</p>
                            <h3 class="text-xl font-bold text-purple-900">
                                Rp {{ number_format($jadwal->pemesanans()->where('status_pemesanan', 'Lunas')->sum('total_bayar'), 0, ',', '.') }}
                            </h3>
                        </div>
                        <i class="fas fa-money-bill-wave text-purple-500 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        @if($jadwal->pemesanans->count() > 0)
        <div class="border-t border-gray-200 p-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Pemesanan Terbaru</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($jadwal->pemesanans->take(10) as $pemesanan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $pemesanan->kode_transaksi }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pemesanan->user->username ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pemesanan->jenis_pemesanan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $pemesanan->status_pemesanan == 'Lunas' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $pemesanan->status_pemesanan == 'Menunggu Bayar' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $pemesanan->status_pemesanan == 'Dibatalkan' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $pemesanan->status_pemesanan == 'Kadaluarsa' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $pemesanan->status_pemesanan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('d M Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($jadwal->pemesanans->count() > 10)
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">Dan {{ $jadwal->pemesanans->count() - 10 }} pemesanan lainnya...</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Metadata -->
        <div class="border-t border-gray-200 bg-gray-50 p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sistem</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">ID Jadwal</p>
                    <p class="text-gray-900 font-medium">{{ $jadwal->jadwal_id }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Dibuat Oleh</p>
                    <p class="text-gray-900 font-medium">{{ $jadwal->createdBy->username ?? 'System' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Dibuat Pada</p>
                    <p class="text-gray-900 font-medium">{{ $jadwal->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection