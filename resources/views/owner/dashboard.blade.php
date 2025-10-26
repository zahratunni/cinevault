@extends('layouts.owner')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <form id="filterForm" method="GET" action="{{ route('owner.dashboard') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('owner.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" title="Reset Filter">
                        <i class="fas fa-redo"></i>
                    </a>
                    <a href="{{ route('owner.dashboard.export-pdf') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition inline-flex items-center justify-center" title="Export PDF" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>
            </div>


        </form>
    </div>

    <!-- Main Revenue Card -->
    <div class="bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-lg shadow-xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-2">Total Pemasukan</p>
                <h2 class="text-5xl font-bold mb-3">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        @if($revenueChange > 0)
                            <i class="fas fa-arrow-up mr-2"></i>
                            <span class="text-lg font-semibold">+{{ number_format($revenueChange, 1) }}%</span>
                        @elseif($revenueChange < 0)
                            <i class="fas fa-arrow-down mr-2"></i>
                            <span class="text-lg font-semibold">{{ number_format($revenueChange, 1) }}%</span>
                        @else
                            <i class="fas fa-minus mr-2"></i>
                            <span class="text-lg font-semibold">0%</span>
                        @endif
                        <span class="text-purple-200 text-sm ml-2">vs periode sebelumnya</span>
                    </div>
                </div>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-6">
                <i class="fas fa-money-bill-wave text-6xl"></i>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- Total Transactions -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Transaksi</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalTransactions) }}</h3>
                    <p class="text-xs text-gray-500 mt-2">Transaksi lunas</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-receipt text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Tickets -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Tiket Terjual</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalTickets) }}</h3>
                    <p class="text-xs text-gray-500 mt-2">Tiket</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-ticket-alt text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Transaction -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Rata-rata Transaksi</p>
                    <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</h3>
                    <p class="text-xs text-gray-500 mt-2">Per transaksi</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-chart-line text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Online vs Offline Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Online Sales -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Penjualan Online</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                    <i class="fas fa-laptop mr-1"></i>Online
                </span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Pemasukan</span>
                    <span class="text-2xl font-bold text-gray-800">Rp {{ number_format($onlineRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Jumlah Transaksi</span>
                    <span class="text-xl font-bold text-gray-800">{{ number_format($onlineCount) }}</span>
                </div>
                <div class="pt-3 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 font-medium">Kontribusi</span>
                        <span class="text-2xl font-bold text-blue-600">
                            {{ $totalRevenue > 0 ? number_format(($onlineRevenue / $totalRevenue) * 100, 1) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offline Sales -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Penjualan Offline</h3>
                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                    <i class="fas fa-store mr-1"></i>Walk-in
                </span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Pemasukan</span>
                    <span class="text-2xl font-bold text-gray-800">Rp {{ number_format($offlineRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Jumlah Transaksi</span>
                    <span class="text-xl font-bold text-gray-800">{{ number_format($offlineCount) }}</span>
                </div>
                <div class="pt-3 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 font-medium">Kontribusi</span>
                        <span class="text-2xl font-bold text-green-600">
                            {{ $totalRevenue > 0 ? number_format(($offlineRevenue / $totalRevenue) * 100, 1) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Trend Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Pemasukan</h3>
        <canvas id="revenueChart" class="w-full" style="max-height: 350px;"></canvas>
    </div>

    <!-- Top Films -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Film Terlaris (Top 5)</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($topFilms as $index => $film)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                            <span class="text-white font-bold text-xl">#{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-lg truncate">{{ $film->judul }}</p>
                            <p class="text-sm text-gray-600">{{ $film->genre }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-purple-100 text-purple-800">
                                <i class="fas fa-ticket-alt mr-2"></i> {{ $film->total_bookings ?? 0 }} tiket
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-film text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-500">Belum ada data dalam periode ini</p>
                    </div>
                @endforelse
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
                label: 'Pemasukan (Rp)',
                data: {!! json_encode($revenueData->pluck('total')) !!},
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointBackgroundColor: 'rgb(147, 51, 234)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
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

        const form = document.getElementById('filterForm');
        form.querySelector('input[name="start_date"]').value = startDate;
        form.querySelector('input[name="end_date"]').value = endDate;
        form.submit();
    }
</script>
@endpush