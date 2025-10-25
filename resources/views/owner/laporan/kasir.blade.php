@extends('layouts.owner')

@section('title', 'Laporan Per Kasir')
@section('page-title', 'Laporan Per Kasir')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('owner.laporan.kasir') }}" class="space-y-4">
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
                    <a href="{{ route('owner.laporan.kasir') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" title="Reset Filter">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Filters -->
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="setQuickFilter('today')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-purple-100 hover:text-purple-700 transition">Hari Ini</button>
                <button type="button" onclick="setQuickFilter('week')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-purple-100 hover:text-purple-700 transition">7 Hari Terakhir</button>
                <button type="button" onclick="setQuickFilter('month')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-purple-100 hover:text-purple-700 transition">30 Hari Terakhir</button>
                <button type="button" onclick="setQuickFilter('year')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-purple-100 hover:text-purple-700 transition">Tahun Ini</button>
            </div>
        </form>
    </div>

    <!-- Info Banner -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Laporan ini hanya menampilkan transaksi <strong>Offline (Walk-in)</strong> yang dilakukan oleh kasir. Transaksi online tidak termasuk dalam laporan ini.
                </p>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-100 mb-1">Total Pemasukan Offline</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Transaksi</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalTransactions) }}</h3>
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
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-ticket-alt text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Kasir Performance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kasirs as $index => $kasir)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4 rounded-t-lg">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center">
                                <span class="text-2xl font-bold text-purple-600">{{ substr($kasir->username, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-purple-100">Kasir</p>
                            <h3 class="text-lg font-bold text-white truncate">{{ $kasir->nama_lengkap ?? $kasir->username }}</h3>
                            <p class="text-xs text-purple-100">@{{ $kasir->username }}</p>
                        </div>
                        @if($index < 3)
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center">
                                    <span class="text-sm font-bold text-gray-800">#{{ $index + 1 }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="p-6 space-y-4">
                    <!-- Revenue -->
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Total Pemasukan</span>
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($kasir->total_revenue, 0, ',', '.') }}</span>
                    </div>

                    <!-- Transactions -->
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Jumlah Transaksi</span>
                        <span class="text-lg font-bold text-gray-900">{{ number_format($kasir->total_transactions) }}</span>
                    </div>

                    <!-- Tickets -->
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Tiket Terjual</span>
                        <span class="text-lg font-bold text-gray-900">
                            <i class="fas fa-ticket-alt text-purple-600 mr-1"></i>
                            {{ number_format($kasir->total_tickets) }}
                        </span>
                    </div>

                    <!-- Average -->
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Rata-rata Transaksi</span>
                        <span class="text-sm font-semibold text-purple-600">Rp {{ number_format($kasir->average_per_transaction, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Footer Badge -->
                <div class="bg-gray-50 px-6 py-3 rounded-b-lg">
                    @if($kasir->total_revenue > 0)
                        <div class="flex items-center justify-center">
                            <span class="text-xs text-gray-500">
                                Kontribusi: 
                                <span class="font-bold text-purple-600">
                                    {{ $totalRevenue > 0 ? number_format(($kasir->total_revenue / $totalRevenue) * 100, 1) : 0 }}%
                                </span>
                            </span>
                        </div>
                    @else
                        <div class="flex items-center justify-center">
                            <span class="text-xs text-gray-400">Belum ada transaksi</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3">
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <i class="fas fa-user-tie text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data kasir</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Kasir Performance Chart -->
    @if($kasirs->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Perbandingan Pemasukan Per Kasir</h3>
        <canvas id="kasirRevenueChart" class="w-full" style="max-height: 400px;"></canvas>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
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

    // Kasir Revenue Chart
    @if($kasirs->count() > 0)
    const kasirCtx = document.getElementById('kasirRevenueChart').getContext('2d');
    const kasirChart = new Chart(kasirCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($kasirs->pluck('username')) !!},
            datasets: [{
                label: 'Pemasukan (Rp)',
                data: {!! json_encode($kasirs->pluck('total_revenue')) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 2
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
    @endif
</script>
@endpush