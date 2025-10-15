@extends('layouts.admin')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.pelanggans.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pelanggan
        </a>
    </div>

    <!-- Action Buttons -->
    @if($totalBookings == 0)
    <div class="flex flex-wrap gap-3 mb-6">
        <form action="{{ route('admin.pelanggans.destroy', $pelanggan->user_id) }}" method="POST" class="inline"
            onsubmit="return confirm('Yakin ingin menghapus pelanggan ini? Pelanggan belum memiliki riwayat transaksi.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                <i class="fas fa-trash mr-2"></i>
                Hapus Pelanggan
            </button>
        </form>
        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-sm text-blue-700">
            <i class="fas fa-info-circle mr-2"></i>
            Pelanggan ini belum memiliki transaksi dan dapat dihapus
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
            <div>
                <h4 class="text-sm font-medium text-yellow-900">Pelanggan Tidak Dapat Dihapus</h4>
                <p class="text-sm text-yellow-700 mt-1">
                    Pelanggan ini memiliki <strong>{{ $totalBookings }} riwayat transaksi</strong> dan tidak dapat dihapus untuk menjaga integritas data dan audit trail.
                </p>
            </div>
        </div>
    </div>
    @endif
    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
        <div class="flex items-start space-x-6">
            <div class="flex-shrink-0">
                <div class="h-24 w-24 rounded-full bg-purple-500 flex items-center justify-center text-white text-4xl font-bold">
                    {{ substr($pelanggan->username, 0, 1) }}
                </div>
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $pelanggan->nama_lengkap ?? $pelanggan->username }}</h1>
                <p class="text-lg text-gray-600 mb-4">Username: {{ $pelanggan->username }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-base text-gray-900">{{ $pelanggan->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">No. Telepon</p>
                        <p class="text-base text-gray-900">{{ $pelanggan->no_telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Member Sejak</p>
                        <p class="text-base text-gray-900">{{ $pelanggan->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        
        <!-- Total Booking -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Booking</p>
                    <h3 class="text-3xl font-bold text-blue-600">{{ $totalBookings }}</h3>
                </div>
                <i class="fas fa-ticket-alt text-blue-500 text-3xl"></i>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Lunas</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ $completedBookings }}</h3>
                </div>
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>

        <!-- Total Spending -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Pengeluaran</p>
                    <h3 class="text-xl font-bold text-purple-600">
                        Rp {{ number_format($totalSpending, 0, ',', '.') }}
                    </h3>
                </div>
                <i class="fas fa-money-bill-wave text-purple-500 text-3xl"></i>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Menunggu Bayar</p>
                    <h3 class="text-3xl font-bold text-yellow-600">{{ $pendingBookings }}</h3>
                </div>
                <i class="fas fa-clock text-yellow-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    @if($favoriteGenre)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Preferensi Pelanggan</h3>
        <div class="flex items-center space-x-4">
            <div>
                <p class="text-sm text-gray-500">Genre Favorit</p>
                <p class="text-lg font-semibold text-gray-900">{{ $favoriteGenre->genre }}</p>
                <p class="text-xs text-gray-500">{{ $favoriteGenre->total }} film ditonton</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Riwayat Booking -->
    @if($recentBookings->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">Riwayat Booking</h3>
        
        <div class="space-y-4">
            @foreach($recentBookings as $booking)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        @if($booking->jadwal && $booking->jadwal->film)
                        <img src="{{ asset($booking->jadwal->film->poster_url) }}" 
                            alt="{{ $booking->jadwal->film->judul }}" 
                            class="h-20 w-14 object-cover rounded flex-shrink-0">
                        @endif
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $booking->kode_transaksi }}</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $booking->jadwal->film->judul ?? 'N/A' }}
                            </p>
                            <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-600">
                                <span><i class="fas fa-door-open mr-1"></i>{{ $booking->jadwal->studio->nama_studio ?? 'N/A' }}</span>
                                <span><i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($booking->jadwal->tanggal_tayang ?? now())->format('d M Y') }}</span>
                                <span><i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($booking->jadwal->jam_mulai ?? now())->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-3 mt-2 text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-shopping-bag mr-1"></i>{{ $booking->jenis_pemesanan }}
                                </span>
                                @if($booking->pembayaran)
                                <span class="text-gray-600">
                                    <i class="fas fa-credit-card mr-1"></i>{{ $booking->pembayaran->metode_bayar }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="font-semibold text-gray-900">Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}</p>
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full mt-2
                            {{ $booking->status_pemesanan == 'Lunas' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->status_pemesanan == 'Menunggu Bayar' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->status_pemesanan == 'Dibatalkan' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $booking->status_pemesanan == 'Kadaluarsa' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ $booking->status_pemesanan }}
                        </span>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ \Carbon\Carbon::parse($booking->tanggal_pemesanan)->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($pelanggan->pemesanans->count() > 10)
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">Dan {{ $pelanggan->pemesanans->count() - 10 }} booking lainnya...</p>
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <i class="fas fa-ticket-alt text-gray-400 text-4xl mb-3"></i>
        <p class="text-gray-600">Pelanggan ini belum memiliki riwayat booking</p>
    </div>
    @endif

</div>
@endsection