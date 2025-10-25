@extends('layouts.owner')

@section('title', 'Laporan Per Periode')
@section('page-title', 'Laporan Per Periode')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('owner.laporan.periode') }}" class="space-y-4">
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
                    <a href="{{ route('owner.laporan.periode') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" title="Reset Filter">
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

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-100 mb-1">Total Pemasukan</p>
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

        <!-- Average Per Day -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Rata-rata Per Hari</p>
                    <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($averagePerDay, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Peak Day -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Hari Tertinggi</p>
                    @if($peakDay)
                        <h3 class="text-xl font-bold text-gray-800">Rp {{ number_format($peakDay->total_revenue, 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($peakDay->date)->format('d M Y') }}</p>
                    @else
                        <h3 class="text-xl font-bold text-gray-400">-</h3>
                    @endif
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-trophy text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Breakdown Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Pemasukan Harian</h3>
        <canvas id="dailyRevenueChart" class="w-full" style="max-height: 400px;"></canvas>
    </div>

    <!-- Online vs Offline Comparison Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Perbandingan Online vs Offline Harian</h3>
        <canvas id="comparisonChart" class="w-full" style="max-height: 400px;"></canvas>
    </div>

    <!-- Daily Detail Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Detail Harian</h3>
            <span class="text-sm text-gray-600">{{ $processedDaily->count() }} Hari</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Online</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Offline</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pemasukan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($processedDaily as $data)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($data->date)->translatedFormat('l') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">Rp {{ number_format($data->online_revenue, 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500">{{ $data->online_transactions }} trx</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">Rp {{ number_format($data->offline_revenue, 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500">{{ $data->offline_transactions }} trx</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-bold text-gray-900">Rp {{ number_format($data->total_revenue, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $data->total_transactions }} trx
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($peakDay && $data->date == $peakDay->date)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-crown mr-1"></i> Peak
                                    </span>
                                @elseif($data->total_revenue >= $averagePerDay)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-up mr-1"></i> Above Avg
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-arrow-down mr-1"></i> Below Avg
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-calendar-times text-gray-400 text-4xl mb-2"></i>
                                <p class="text-gray-500">Belum ada data dalam periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($processedDaily->count() > 0)
                    <tfoot class="bg-gray-50">
                        <tr class="font-bold">
                            <td class="px-6 py-4 text-sm text-gray-900">TOTAL</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-900">
                                Rp {{ number_format($processedDaily->sum('online_revenue'), 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-900">
                                Rp {{ number_format($processedDaily->sum('offline_revenue'), 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-900">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-900">
                                {{ number_format($totalTransactions) }} trx
                            </td>
                            <td class="px-6 py-4"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

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

    @if($processedDaily->count() > 0)
    // Daily Revenue Chart
    const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    const dailyChart = new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($processedDaily->pluck('date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d M');
            })) !!},
            datasets: [{
                label: 'Total Pemasukan (Rp)',
                data: {!! json_encode($processedDaily->pluck('total_revenue')) !!},
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 2,
                pointBackgroundColor: 'rgb(147, 51, 234)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
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

    // Online vs Offline Comparison Chart
    const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
    const comparisonChart = new Chart(comparisonCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($processedDaily->pluck('date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d M');
            })) !!},
            datasets: [
                {
                    label: 'Online',
                    data: {!! json_encode($processedDaily->pluck('online_revenue')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2
                },
                {
                    label: 'Offline',
                    data: {!! json_encode($processedDaily->pluck('offline_revenue')) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    stacked: false,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    stacked: false
                }
            }
        }
    });
    @endif
</script>
@endpush