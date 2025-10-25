<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Dashboard Owner - CineVault</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 3px solid #7c3aed;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #7c3aed;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 11px;
        }
        
        .meta-info {
            background: #f3f4f6;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .meta-info table {
            width: 100%;
        }
        
        .meta-info td {
            padding: 3px 0;
        }
        
        .meta-info strong {
            color: #7c3aed;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            color: #7c3aed;
            border-bottom: 2px solid #7c3aed;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 10px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            text-align: center;
        }
        
        .stat-card .label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .stat-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }
        
        .stat-card .sub {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 3px;
        }
        
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .breakdown-table th {
            background: #7c3aed;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        
        .breakdown-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .breakdown-table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .comparison {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        
        .comparison-item {
            display: table-cell;
            width: 50%;
            padding: 15px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .comparison-item.online {
            background: #dbeafe;
        }
        
        .comparison-item.offline {
            background: #d1fae5;
        }
        
        .comparison-item h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #1f2937;
        }
        
        .comparison-item .amount {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .comparison-item .count {
            font-size: 11px;
            color: #6b7280;
        }
        
        .top-films {
            margin-top: 10px;
        }
        
        .film-item {
            padding: 8px;
            border: 1px solid #e5e7eb;
            margin-bottom: 5px;
            background: #f9fafb;
        }
        
        .film-item .rank {
            display: inline-block;
            width: 25px;
            height: 25px;
            background: #7c3aed;
            color: white;
            text-align: center;
            line-height: 25px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 11px;
            margin-right: 10px;
        }
        
        .film-item .title {
            font-weight: bold;
            color: #1f2937;
        }
        
        .film-item .genre {
            color: #6b7280;
            font-size: 10px;
        }
        
        .film-item .tickets {
            float: right;
            background: #7c3aed;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        
        .positive {
            color: #10b981;
        }
        
        .negative {
            color: #ef4444;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    
    <!-- Header -->
    <div class="header">
        <h1>🎬 CINEVAULT</h1>
        <p>Laporan Dashboard Owner</p>
    </div>
    
    <!-- Meta Information -->
    <div class="meta-info">
        <table>
            <tr>
                <td width="25%"><strong>Periode:</strong></td>
                <td>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</td>
                <td width="25%"><strong>Dicetak:</strong></td>
                <td>{{ $generatedDate }}</td>
            </tr>
            <tr>
                <td><strong>Dicetak oleh:</strong></td>
                <td>{{ Auth::user()->username }} (Owner)</td>
                <td><strong>Total Hari:</strong></td>
                <td>{{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} hari</td>
            </tr>
        </table>
    </div>
    
    <!-- Main Statistics -->
    <div class="section">
        <div class="section-title">Ringkasan Keuangan</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">Total Pemasukan</div>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="sub">
                    @if($revenueChange > 0)
                        <span class="positive">▲ +{{ number_format($revenueChange, 1) }}%</span>
                    @elseif($revenueChange < 0)
                        <span class="negative">▼ {{ number_format($revenueChange, 1) }}%</span>
                    @else
                        ━ 0%
                    @endif
                </div>
            </div>
            <div class="stat-card">
                <div class="label">Total Tiket Terjual</div>
                <div class="value">{{ number_format($totalTickets) }}</div>
                <div class="sub">Tiket</div>
            </div>
            <div class="stat-card">
                <div class="label">Total Transaksi</div>
                <div class="value">{{ number_format($totalTransactions) }}</div>
                <div class="sub">Transaksi Lunas</div>
            </div>
            <div class="stat-card">
                <div class="label">Rata-rata Transaksi</div>
                <div class="value">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</div>
                <div class="sub">Per Transaksi</div>
            </div>
        </div>
    </div>
    
    <!-- Online vs Offline Comparison -->
    <div class="section">
        <div class="section-title">Perbandingan Penjualan Online vs Offline</div>
        <div class="comparison">
            <div class="comparison-item online">
                <h3>💻 Penjualan Online</h3>
                <div class="amount">Rp {{ number_format($onlineRevenue, 0, ',', '.') }}</div>
                <div class="count">{{ number_format($onlineCount) }} Transaksi | {{ $totalRevenue > 0 ? number_format(($onlineRevenue / $totalRevenue) * 100, 1) : 0 }}%</div>
            </div>
            <div class="comparison-item offline">
                <h3>🏪 Penjualan Offline (Walk-in)</h3>
                <div class="amount">Rp {{ number_format($offlineRevenue, 0, ',', '.') }}</div>
                <div class="count">{{ number_format($offlineCount) }} Transaksi | {{ $totalRevenue > 0 ? number_format(($offlineRevenue / $totalRevenue) * 100, 1) : 0 }}%</div>
            </div>
        </div>
    </div>
    
    <!-- Top Films -->
    <div class="section">
        <div class="section-title">Film Terlaris (Top 5)</div>
        <div class="top-films">
            @forelse($topFilms as $index => $film)
                <div class="film-item">
                    <span class="rank">#{{ $index + 1 }}</span>
                    <span class="title">{{ $film->judul }}</span>
                    <span class="genre"> - {{ $film->genre }}</span>
                    <span class="tickets">🎟️ {{ $film->total_bookings ?? 0 }} tiket</span>
                </div>
            @empty
                <p style="text-align: center; color: #6b7280; padding: 20px;">Belum ada data dalam periode ini</p>
            @endforelse
        </div>
    </div>
    
    <!-- Page Break -->
    <div class="page-break"></div>
    
    <!-- Booking Status Breakdown -->
    <div class="section">
        <div class="section-title">Status Pemesanan</div>
        <table class="breakdown-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: right;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalBookings = $bookingStatus->sum('total');
                @endphp
                @foreach($bookingStatus as $status)
                    <tr>
                        <td>{{ $status->status_pemesanan }}</td>
                        <td style="text-align: center;">{{ number_format($status->total) }}</td>
                        <td style="text-align: right;">
                            {{ $totalBookings > 0 ? number_format(($status->total / $totalBookings) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background: #e5e7eb;">
                    <td>TOTAL</td>
                    <td style="text-align: center;">{{ number_format($totalBookings) }}</td>
                    <td style="text-align: right;">100%</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Recent Transactions -->
    <div class="section">
        <div class="section-title">Transaksi Terbaru (10 Terakhir)</div>
        <table class="breakdown-table">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Film</th>
                    <th>Customer</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->kode_transaksi }}</td>
                        <td>{{ Str::limit($transaction->jadwal->film->judul ?? 'N/A', 25) }}</td>
                        <td>{{ $transaction->user->username ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->tanggal_pemesanan)->format('d M Y') }}</td>
                        <td>{{ $transaction->jenis_pemesanan }}</td>
                        <td style="text-align: right;">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #6b7280; padding: 20px;">
                            Belum ada transaksi dalam periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Revenue Data Table -->
    <div class="section">
        <div class="section-title">{{ $chartLabel }}</div>
        <table class="breakdown-table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th style="text-align: right;">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueData as $data)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($data->period)->format('d M Y') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($data->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background: #e5e7eb;">
                    <td>TOTAL</td>
                    <td style="text-align: right;">Rp {{ number_format($revenueData->sum('total'), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem CineVault</p>
        <p>© {{ date('Y') }} CineVault Cinema Management System. All rights reserved.</p>
    </div>
    
</body>
</html>