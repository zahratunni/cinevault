<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket - {{ $pemesanan->kode_transaksi }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .ticket-item {
                page-break-after: always;
                margin: 0;
            }
            .ticket-item:last-child {
                page-break-after: auto;
            }
        }
        
        .ticket-card {
            width: 280px;
            margin: 0 auto;
            font-family: 'Courier New', monospace;
        }
        
        .dotted-line {
            border-top: 2px dashed #cbd5e0;
            margin: 12px 0;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Button Print -->
    <div class="no-print max-w-4xl mx-auto py-6 px-4">
        <div class="flex gap-3 mb-4">
            <a href="{{ route('kasir.dashboard') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition text-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button onclick="window.print()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition">
                <i class="fas fa-print mr-2"></i> Print {{ $pemesanan->detailPemesanans->count() }} Tiket
            </button>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
            <p class="text-sm text-blue-800">
                <i class="fas fa-info-circle mr-2"></i>
                Total <strong>{{ $pemesanan->detailPemesanans->count() }} tiket</strong> akan dicetak
            </p>
        </div>
    </div>

    <!-- Tiket Container -->
    <div class="no-print max-w-4xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pb-8">
            @foreach($pemesanan->detailPemesanans as $detail)
            <div class="ticket-card bg-white rounded-lg shadow-md overflow-hidden border border-gray-300">
                
                <!-- Header -->
                <div class="bg-gray-900 text-white px-4 py-3 text-center">
                    <h2 class="text-lg font-bold">CINEVAULT</h2>
                    <p class="text-xs text-gray-400">CINEMA TICKET</p>
                </div>

                <!-- Content -->
                <div class="px-4 py-3 text-sm">
                    
                    <!-- Film -->
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 uppercase">Film</p>
                        <p class="font-bold text-gray-900 leading-tight">{{ $pemesanan->jadwal->film->judul }}</p>
                    </div>

                    <div class="dotted-line"></div>

                    <!-- Studio & Date -->
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <p class="text-xs text-gray-500">Studio</p>
                            <p class="font-semibold text-gray-900">{{ $pemesanan->jadwal->studio->nama_studio }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Time -->
                    <div class="mb-3">
                        <p class="text-xs text-gray-500">Waktu</p>
                        <p class="font-semibold text-gray-900">{{ $pemesanan->jadwal->jam_mulai }}</p>
                    </div>

                    <div class="dotted-line"></div>

                    <!-- Seat (Highlighted) -->
                    <div class="bg-yellow-100 border-2 border-yellow-400 rounded p-3 mb-3 text-center">
                        <p class="text-xs text-gray-600 mb-1">KURSI</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $detail->kursi->kode_kursi }}</p>
                    </div>

                    <!-- Price -->
                    <div class="mb-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Harga</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($detail->harga_per_kursi, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="dotted-line"></div>

                    <!-- Booking Code -->
                    <div class="mb-2">
                        <p class="text-xs text-gray-500 text-center">Kode Booking</p>
                        <p class="font-mono text-xs text-gray-900 text-center break-all">{{ $pemesanan->kode_transaksi }}</p>
                    </div>

                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                    <p class="text-xs text-gray-500 text-center">
                        ✓ LUNAS
                    </p>
                </div>

            </div>
            @endforeach
        </div>
    </div>

    <!-- Print Version (Hidden on screen, visible when printing) -->
    <div style="display: none;">
        @foreach($pemesanan->detailPemesanans as $detail)
        <div class="ticket-item" style="padding: 20px;">
            <div class="ticket-card bg-white rounded-lg shadow-md overflow-hidden border border-gray-300">
                
                <!-- Header -->
                <div class="bg-gray-900 text-white px-4 py-3 text-center">
                    <h2 class="text-lg font-bold">CINEVAULT</h2>
                    <p class="text-xs text-gray-400">CINEMA TICKET</p>
                </div>

                <!-- Content -->
                <div class="px-4 py-3 text-sm">
                    
                    <!-- Film -->
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 uppercase">Film</p>
                        <p class="font-bold text-gray-900 leading-tight">{{ $pemesanan->jadwal->film->judul }}</p>
                    </div>

                    <div class="dotted-line"></div>

                    <!-- Studio & Date -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 8px;">
                        <div>
                            <p class="text-xs text-gray-500">Studio</p>
                            <p class="font-semibold text-gray-900">{{ $pemesanan->jadwal->studio->nama_studio }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Time -->
                    <div class="mb-3">
                        <p class="text-xs text-gray-500">Waktu</p>
                        <p class="font-semibold text-gray-900">{{ $pemesanan->jadwal->jam_mulai }}</p>
                    </div>

                    <div class="dotted-line"></div>

                    <!-- Seat -->
                    <div style="background-color: #fef3c7; border: 2px solid #fbbf24; border-radius: 4px; padding: 12px; margin-bottom: 12px; text-align: center;">
                        <p class="text-xs text-gray-600" style="margin-bottom: 4px;">KURSI</p>
                        <p style="font-size: 28px; font-weight: bold; color: #111;">{{ $detail->kursi->kode_kursi }}</p>
                    </div>

                    <!-- Price -->
                    <div class="mb-2" style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="text-xs text-gray-500">Harga</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($detail->harga_per_kursi, 0, ',', '.') }}</span>
                    </div>

                    <div class="dotted-line"></div>

                    <!-- Booking Code -->
                    <div class="mb-2">
                        <p class="text-xs text-gray-500 text-center">Kode Booking</p>
                        <p class="font-mono text-xs text-gray-900 text-center" style="word-break: break-all;">{{ $pemesanan->kode_transaksi }}</p>
                    </div>

                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                    <p class="text-xs text-gray-500 text-center">✓ LUNAS</p>
                </div>

            </div>
        </div>
        @endforeach
    </div>

</body>
</html>