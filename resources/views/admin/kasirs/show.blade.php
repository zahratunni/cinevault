@extends('layouts.admin')

@section('title', 'Detail Kasir')
@section('page-title', 'Detail Kasir')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.kasirs.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Kasir
        </a>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('admin.kasirs.edit', $kasir->user_id) }}" 
            class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
            <i class="fas fa-edit mr-2"></i>
            Edit Data
        </a>
        <form action="{{ route('admin.kasirs.destroy', $kasir->user_id) }}" method="POST" class="inline"
            onsubmit="return confirm('Yakin ingin menghapus kasir ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                <i class="fas fa-trash mr-2"></i>
                Hapus Kasir
            </button>
        </form>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
        <div class="flex items-start space-x-6">
            <div class="flex-shrink-0">
                <div class="h-24 w-24 rounded-full bg-blue-500 flex items-center justify-center text-white text-4xl font-bold">
                    {{ substr($kasir->username, 0, 1) }}
                </div>
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $kasir->nama_lengkap }}</h1>
                <p class="text-lg text-gray-600 mb-4">Username: {{ $kasir->username }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-base text-gray-900">{{ $kasir->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">No. Telepon</p>
                        <p class="text-base text-gray-900">{{ $kasir->no_telepon }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Role</p>
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $kasir->role }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        
        <!-- Total Transaksi -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Transaksi</p>
                    <h3 class="text-3xl font-bold text-blue-600">{{ $kasir->pembayarans->count() }}</h3>
                </div>
                <i class="fas fa-receipt text-blue-500 text-3xl"></i>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Pendapatan</p>
                    <h3 class="text-xl font-bold text-green-600">
                        Rp {{ number_format($kasir->pembayarans->sum('nominal_dibayar'), 0, ',', '.') }}
                    </h3>
                </div>
                <i class="fas fa-money-bill-wave text-green-500 text-3xl"></i>
            </div>
        </div>

        <!-- Jadwal Dibuat -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Jadwal Dibuat</p>
                    <h3 class="text-3xl font-bold text-purple-600">{{ $kasir->jadwals->count() }}</h3>
                </div>
                <i class="fas fa-calendar-alt text-purple-500 text-3xl"></i>
            </div>
        </div>

        <!-- Lama Bekerja -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Lama Bekerja</p>
                    <h3 class="text-3xl font-bold text-yellow-600">{{ $kasir->created_at->diffInDays(now()) }}</h3>
                    <p class="text-xs text-gray-500">hari</p>
                </div>
                <i class="fas fa-clock text-yellow-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    @if($recentTransactions->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">Transaksi Terbaru</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Film</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentTransactions as $transaksi)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $transaksi->pemesanan->kode_transaksi }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaksi->pemesanan->user->username ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $transaksi->pemesanan->jadwal->film->judul ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaksi->metode_bayar }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            Rp {{ number_format($transaksi->nominal_dibayar, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $transaksi->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $transaksi->status_pembayaran == 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $transaksi->status_pembayaran == 'Gagal' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $transaksi->status_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pembayaran)->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($kasir->pembayarans->count() > 10)
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">Dan {{ $kasir->pembayarans->count() - 10 }} transaksi lainnya...</p>
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-8 mb-6 text-center">
        <i class="fas fa-receipt text-gray-400 text-4xl mb-3"></i>
        <p class="text-gray-600">Belum ada transaksi yang ditangani kasir ini</p>
    </div>
    @endif

    <!-- Metadata -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sistem</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">ID User</p>
                <p class="text-gray-900 font-medium">{{ $kasir->user_id }}</p>
            </div>
            <div>
                <p class="text-gray-500">Terdaftar Pada</p>
                <p class="text-gray-900 font-medium">{{ $kasir->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Terakhir Diupdate</p>
                <p class="text-gray-900 font-medium">{{ $kasir->updated_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

</div>
@endsection