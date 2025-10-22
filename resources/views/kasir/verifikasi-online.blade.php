@extends('layouts.kasir')

@section('title', 'Verifikasi Pembayaran Online')
@section('page-title', 'Verifikasi Pembayaran Online')

@section('content')
<div class="space-y-6">
    
    <!-- Header dengan Badge -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Verifikasi Pembayaran Online</h2>
            <p class="text-gray-600 mt-1">Kelola pembayaran dari customer online</p>
        </div>
        <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg font-semibold">
            <i class="fas fa-clock"></i> {{ $stats['pending'] }} Menunggu
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pending -->
        <div class="bg-yellow-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pending</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['pending'] }}</p>
                    <p class="text-yellow-100 text-sm mt-1">Menunggu Verifikasi</p>
                </div>
                <div class="bg-yellow-600 rounded-full p-4">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Lunas -->
        <div class="bg-green-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Lunas</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['lunas'] }}</p>
                    <p class="text-green-100 text-sm mt-1">Terverifikasi</p>
                </div>
                <div class="bg-green-600 rounded-full p-4">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="bg-red-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Ditolak</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['gagal'] }}</p>
                    <p class="text-red-100 text-sm mt-1">Pembayaran Gagal</p>
                </div>
                <div class="bg-red-600 rounded-full p-4">
                    <i class="fas fa-times-circle text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="{{ route('kasir.verifikasi-online.index', ['status' => 'Pending']) }}" 
                   class="px-6 py-4 text-sm font-medium border-b-2 {{ $status == 'Pending' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-clock mr-2"></i>
                    Pending ({{ $stats['pending'] }})
                </a>
                <a href="{{ route('kasir.verifikasi-online.index', ['status' => 'Lunas']) }}" 
                   class="px-6 py-4 text-sm font-medium border-b-2 {{ $status == 'Lunas' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-check mr-2"></i>
                    Lunas ({{ $stats['lunas'] }})
                </a>
                <a href="{{ route('kasir.verifikasi-online.index', ['status' => 'Gagal']) }}" 
                   class="px-6 py-4 text-sm font-medium border-b-2 {{ $status == 'Gagal' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-times mr-2"></i>
                    Ditolak ({{ $stats['gagal'] }})
                </a>
            </nav>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            @if($pembayarans->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Film</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pembayarans as $pembayaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900">#{{ $pembayaran->pembayaran_id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $pembayaran->tanggal_pembayaran->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $pembayaran->tanggal_pembayaran->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $pembayaran->pemesanan->user->nama_lengkap ?? $pembayaran->pemesanan->user->username }}</div>
                                <div class="text-xs text-gray-500">{{ $pembayaran->pemesanan->kode_transaksi }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $pembayaran->pemesanan->jadwal->film->judul ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $pembayaran->metode_online ?? $pembayaran->metode_bayar }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900">Rp {{ number_format($pembayaran->nominal_dibayar, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pembayaran->status_pembayaran == 'Pending')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($pembayaran->status_pembayaran == 'Lunas')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Lunas
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pembayaran->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-image mr-1"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">Belum upload</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($pembayaran->status_pembayaran == 'Pending')
                                    <a href="{{ route('kasir.verifikasi-online.show', $pembayaran->pembayaran_id) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <i class="fas fa-check-circle mr-1"></i> Verifikasi
                                    </a>
                                @else
                                    <a href="{{ route('kasir.verifikasi-online.show', $pembayaran->pembayaran_id) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $pembayarans->appends(['status' => $status])->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Tidak ada pembayaran dengan status <strong>{{ $status }}</strong></p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto refresh setiap 30 detik untuk update status
setInterval(() => {
    if ('{{ $status }}' === 'Pending') {
        location.reload();
    }
}, 30000);
</script>
@endpush
@endsection