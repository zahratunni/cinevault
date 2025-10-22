@extends('layouts.kasir')

@section('title', 'Pembayaran - Kasir')
@section('page-title', 'Proses Pembayaran Offline')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Form Pembayaran (Kolom Kiri) -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-credit-card mr-2 text-blue-600"></i> Detail Pembayaran
            </h2>

            <form method="POST" action="{{ route('kasir.pembayaran.store', $pemesanan->pemesanan_id) }}" class="space-y-6">
                @csrf

                <!-- Kode Transaksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Transaksi</label>
                    <input type="text" value="{{ $pemesanan->kode_transaksi }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-mono">
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill mr-1"></i> Metode Pembayaran
                    </label>
                    <select name="metode_bayar" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Metode --</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Debit/Kredit">Debit/Kredit</option>
                        <option value="E-Wallet">E-Wallet</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                    </select>
                </div>

                <!-- Nominal Dibayar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calculator mr-1"></i> Nominal Dibayar
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-600 font-medium">Rp</span>
                        <input type="number" name="nominal_dibayar" required min="{{ $pemesanan->total_bayar }}" class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimal: Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</p>
                </div>

                <!-- Button -->
                <div class="flex gap-3 pt-4">
                    <a href="{{ route('kasir.pemesanan.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 rounded-lg transition text-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-lg transition">
                        <i class="fas fa-check-circle mr-2"></i> Proses Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Pemesanan (Kolom Kanan) -->
    <div class="bg-white rounded-lg shadow-sm p-6 h-fit sticky top-20">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-receipt mr-2 text-purple-600"></i> Ringkasan Pemesanan
        </h3>

        <div class="space-y-4">
            <!-- Film -->
            <div class="pb-4 border-b border-gray-200">
                <p class="text-xs text-gray-500 font-medium uppercase">Film</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $pemesanan->jadwal->film->judul }}</p>
            </div>

            <!-- Studio -->
            <div class="pb-4 border-b border-gray-200">
                <p class="text-xs text-gray-500 font-medium uppercase">Studio</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $pemesanan->jadwal->studio->nama_studio }}</p>
            </div>

            <!-- Tanggal & Jam -->
            <div class="pb-4 border-b border-gray-200">
                <p class="text-xs text-gray-500 font-medium uppercase">Jadwal Tayang</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">
                    {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d M Y') }}
                    {{ $pemesanan->jadwal->jam_mulai }}
                </p>
            </div>

            <!-- Kursi -->
            <div class="pb-4 border-b border-gray-200">
                <p class="text-xs text-gray-500 font-medium uppercase">Kursi</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($pemesanan->detailPemesanans as $detail)
                        <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $detail->kursi->kode_kursi }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Jumlah Kursi -->
            <div class="pb-4 border-b border-gray-200">
                <p class="text-xs text-gray-500 font-medium uppercase">Jumlah Kursi</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $pemesanan->detailPemesanans->count() }} Kursi</p>
            </div>

            <!-- Harga Per Kursi -->
            <div class="pb-4 border-b border-gray-200">
                <p class="text-xs text-gray-500 font-medium uppercase">Harga/Kursi</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">Rp {{ number_format($pemesanan->detailPemesanans->first()->harga_per_kursi, 0, ',', '.') }}</p>
            </div>

            <!-- Total Harga -->
            <div class="pt-2">
                <p class="text-xs text-gray-500 font-medium uppercase mb-2">Total Bayar</p>
                <p class="text-3xl font-bold text-green-600">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</p>
            </div>

            <!-- Status -->
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-xs text-yellow-700 font-medium">
                    <i class="fas fa-info-circle mr-1"></i> Status: <span class="font-bold">{{ $pemesanan->status_pemesanan }}</span>
                </p>
            </div>
        </div>
    </div>
</div>

@endsection