@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-redo"></i>
                    </a>
                    <button type="button" onclick="exportToPDF()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                </div>
            </div>

            <!-- Quick Filters -->
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="setQuickFilter('today')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">Hari Ini</button>
                <button type="button" onclick="setQuickFilter('week')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">7 Hari Terakhir</button>
                <button type="button" onclick="setQuickFilter('month')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">30 Hari Terakhir</button>
                <button type="button" onclick="setQuickFilter('year')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">Tahun Ini</button>
            </div>
        </form>
    </div>

    <!-- Stats Cards Row 1 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Films -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Film</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalFilms }}</h3>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="text-green-600">{{ $playingNowCount }}</span> Playing Now
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-film text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Jadwal -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Jadwal Aktif</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalJadwals }}</h3>
                    <p class="text-xs text-gray-500 mt-2">
                        Jadwal yang berjalan
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-calendar-alt text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Pelanggan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Pelanggan</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalPelanggan }}</h3>
                    <p class="text-xs text-gray-500 mt-2">
                        Pengguna terdaftar
                    </p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Kasir -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Kasir</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalKasir }}</h3>
                    <p class="text-xs text-gray-500 mt-2">
                        Staff kasir aktif
                    </p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-user-tie text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 2 - Revenue & Bookings -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        
        <!-- Total Revenue -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-100 mb-1">Total Pendapatan (Periode)</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="text-xs text-green-100 mt-2">
                        @if($revenueChange > 0)
                            <i class="fas fa-arrow-up"></i> +{{ number_format($revenueChange, 1) }}% dari periode sebelumnya
                        @elseif($revenueChange < 0)
                            <i class="fas fa-arrow-down"></i> {{ number_format($revenueChange, 1) }}% dari periode sebelumnya
                        @else
                            <i class="fas fa-minus"></i> Tidak ada perubahan
                        @endif
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-100 mb-1">Total Pemesanan (Periode)</p>
                    <h3 class="text-2xl font-bold">{{ $totalBookings }}</h3>
                    <p class="text-xs text-blue-100 mt-2">
                        <i class="fas fa-calendar"></i> Hari ini: {{ $todayBookings }}
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-ticket-alt text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Bookings -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-orange-100 mb-1">Menunggu Bayar</p>
                    <h3 class="text-2xl font-bold">{{ $pendingBookings }}</h3>
                    <p class="text-xs text-orange-100 mt-2">
                        Pemesanan pending
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $chartLabel }}</h3>
            <canvas id="revenueChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>

        <!-- Booking Status Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Pemesanan (Periode)</h3>
            <canvas id="bookingStatusChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Recent Bookings & Top Films -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Pemesanan Terbaru (Periode)</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($recentBookings as $booking)
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100 last:border-0">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $booking->kode_transaksi }}</p>
                                <p class="text-sm text-gray-600">{{ $booking->jadwal->film->judul ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->user->username ?? 'N/A' }} • {{ \Carbon\Carbon::parse($booking->tanggal_pemesanan)->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-800">Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}</p>
                                <span class="inline-block px-2 py-1 text-xs rounded-full 
                                    {{ $booking->status_pemesanan == 'Lunas' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status_pemesanan == 'Menunggu Bayar' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status_pemesanan == 'Dibatalkan' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $booking->status_pemesanan == 'Kadaluarsa' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $booking->status_pemesanan }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada pemesanan dalam periode ini</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Films -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Film Terpopuler (Periode)</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topFilms as $index => $film)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-sm">#{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 truncate">{{ $film->judul }}</p>
                                <p class="text-sm text-gray-600">{{ $film->genre }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-ticket-alt mr-1"></i> {{ $film->total_bookings ?? 0 }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada data dalam periode ini</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueData->pluck('period')) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($revenueData->pluck('total')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Booking Status Chart
    const statusCtx = document.getElementById('bookingStatusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($bookingStatus->pluck('status_pemesanan')) !!},
            datasets: [{
                data: {!! json_encode($bookingStatus->pluck('total')) !!},
                backgroundColor: [
                    'rgb(34, 197, 94)',   // Green - Lunas
                    'rgb(251, 191, 36)',  // Yellow - Menunggu Bayar
                    'rgb(239, 68, 68)',   // Red - Dibatalkan
                    'rgb(156, 163, 175)'  // Gray - Kadaluarsa
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Quick Filter Functions
    function setQuickFilter(type) {
        const today = new Date();
        const endDate = today.toISOString().split('T')[0];
        let startDate;

        switch(type) {
            case 'today':
                startDate = endDate;
                break;
            case 'week':
                const weekAgo = new Date(today);
                weekAgo.setDate(weekAgo.getDate() - 7);
                startDate = weekAgo.toISOString().split('T')[0];
                break;
            case 'month':
                const monthAgo = new Date(today);
                monthAgo.setDate(monthAgo.getDate() - 30);
                startDate = monthAgo.toISOString().split('T')[0];
                break;
            case 'year':
                startDate = today.getFullYear() + '-01-01';
                break;
        }

        document.querySelector('input[name="start_date"]').value = startDate;
        document.querySelector('input[name="end_date"]').value = endDate;
        document.querySelector('form').submit();
    }

    // Export to PDF (placeholder - implement later)
    function exportToPDF() {
        alert('Fitur Export PDF akan segera hadir!');
        // TODO: Implement PDF export using DomPDF or similar
    }
</script>
@endpush