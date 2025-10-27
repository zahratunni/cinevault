@extends('layouts.kasir')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <a href="{{ route('kasir.kelola.pemesanan') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Pemesanan
    </a>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Pemesanan</h1>
                <p class="text-gray-600">Kode Transaksi: <span class="font-semibold text-blue-600">{{ $pemesanan->kode_transaksi }}</span></p>
            </div>
            <div>
                @if($pemesanan->status_pemesanan == 'Lunas')
                    <span class="px-4 py-2 rounded-full bg-green-100 text-green-800 font-semibold">
                        <i class="fas fa-check-circle mr-1"></i>Lunas
                    </span>
                @elseif($pemesanan->status_pemesanan == 'Menunggu Bayar')
                    <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 font-semibold">
                        <i class="fas fa-clock mr-1"></i>Menunggu Pembayaran
                    </span>
                @else
                    <span class="px-4 py-2 rounded-full bg-red-100 text-red-800 font-semibold">
                        <i class="fas fa-times-circle mr-1"></i>Dibatalkan
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Film -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 flex items-center">
                    <i class="fas fa-film text-blue-600 mr-2"></i>
                    Informasi Film
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Judul Film</p>
                        <p class="font-semibold">{{ $pemesanan->jadwal->film->judul }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Studio</p>
                        <p class="font-semibold">{{ $pemesanan->jadwal->studio->nama_studio }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Tayang</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Waktu</p>
                        <p class="font-semibold">{{ $pemesanan->jadwal->jam_mulai }} - {{ $pemesanan->jadwal->jam_selesai }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Durasi</p>
                        <p class="font-semibold">{{ $pemesanan->jadwal->film->durasi }} menit</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Genre</p>
                        <p class="font-semibold">{{ $pemesanan->jadwal->film->genre }}</p>
                    </div>
                </div>
            </div>

            <!-- Kursi yang Dipesan -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 flex items-center">
                    <i class="fas fa-chair text-blue-600 mr-2"></i>
                    Kursi yang Dipesan
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($pemesanan->detailPemesanans as $detail)
                        <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold">
                            {{ $detail->kursi->kode_kursi }}
                        </span>
                    @endforeach
                </div>
                <p class="mt-4 text-sm text-gray-600">
                    Total: <span class="font-semibold">{{ $pemesanan->detailPemesanans->count() }} kursi</span>
                </p>
            </div>

            <!-- Info Pembayaran -->
            @if($pemesanan->pembayaran)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 flex items-center">
                    <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                    Informasi Pembayaran
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Metode Pembayaran</p>
                        <p class="font-semibold">{{ $pemesanan->pembayaran->metode_bayar }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal Dibayar</p>
                        <p class="font-semibold">Rp {{ number_format($pemesanan->pembayaran->nominal_dibayar, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status Pembayaran</p>
                        <p class="font-semibold">{{ $pemesanan->pembayaran->status_pembayaran }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pembayaran</p>
                        <p class="font-semibold">{{ $pemesanan->pembayaran->tanggal_pembayaran ? \Carbon\Carbon::parse($pemesanan->pembayaran->tanggal_pembayaran)->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Panel Aksi -->
        <div class="lg:col-span-1">
            <!-- Ringkasan Pembayaran -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-bold mb-4">Ringkasan Pembayaran</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga per Kursi</span>
                        <span class="font-semibold">Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Kursi</span>
                        <span class="font-semibold">{{ $pemesanan->detailPemesanans->count() }}</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-bold text-lg">Total</span>
                        <span class="font-bold text-lg text-blue-600">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            @if($pemesanan->status_pemesanan == 'Menunggu Bayar')
            <div class="bg-white rounded-lg shadow-md p-6 space-y-3">
                <h2 class="text-lg font-bold mb-4">Proses Pembayaran</h2>
                
                <!-- Bayar Cash -->
                <button onclick="showCashModal()" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-semibold transition">
                    <i class="fas fa-money-bill-wave mr-2"></i>Bayar Cash
                </button>

                <!-- Bayar Online (Midtrans) -->
                <a href="{{ route('kasir.midtrans.create', $pemesanan->pemesanan_id) }}" 
                   class="block w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold text-center transition">
                    <i class="fas fa-qrcode mr-2"></i>Bayar Online (QRIS/E-Wallet)
                </a>

                <!-- Batalkan -->
                <button onclick="confirmCancel()" class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 font-semibold transition">
                    <i class="fas fa-times mr-2"></i>Batalkan Pemesanan
                </button>
            </div>
            @elseif($pemesanan->status_pemesanan == 'Lunas')
            <div class="bg-white rounded-lg shadow-md p-6">
                <a href="{{ route('kasir.tiket.show', $pemesanan->pemesanan_id) }}" 
                   class="block w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold text-center transition">
                    <i class="fas fa-print mr-2"></i>Cetak Tiket
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Pembayaran Cash -->
<div id="cashModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">Konfirmasi Pembayaran Cash</h3>
        
        <form action="{{ route('kasir.kelola.confirmCash', $pemesanan->pemesanan_id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Total yang Harus Dibayar</label>
                <div class="text-2xl font-bold text-blue-600">
                    Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nominal yang Dibayarkan</label>
                <input type="number" 
                       name="nominal_dibayar" 
                       id="nominal_dibayar"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="Masukkan nominal"
                       min="{{ $pemesanan->total_bayar }}"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kembalian</label>
                <div id="kembalian" class="text-xl font-bold text-green-600">
                    Rp 0
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeCashModal()" 
                        class="flex-1 bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                    Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form Hidden untuk Cancel -->
<form id="cancelForm" action="{{ route('kasir.kelola.cancel', $pemesanan->pemesanan_id) }}" method="POST" class="hidden">
    @csrf
</form>

@endsection

@push('scripts')
<script>
// Modal Cash
function showCashModal() {
    document.getElementById('cashModal').classList.remove('hidden');
}

function closeCashModal() {
    document.getElementById('cashModal').classList.add('hidden');
}

// Hitung Kembalian
document.getElementById('nominal_dibayar')?.addEventListener('input', function() {
    const total = {{ $pemesanan->total_bayar }};
    const dibayar = parseFloat(this.value) || 0;
    const kembalian = dibayar - total;
    
    const kembalianEl = document.getElementById('kembalian');
    if (kembalian >= 0) {
        kembalianEl.textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
        kembalianEl.classList.remove('text-red-600');
        kembalianEl.classList.add('text-green-600');
    } else {
        kembalianEl.textContent = 'Nominal kurang!';
        kembalianEl.classList.remove('text-green-600');
        kembalianEl.classList.add('text-red-600');
    }
});

// Confirm Cancel
function confirmCancel() {
    if (confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?\n\nKursi akan dikembalikan ke pool.')) {
        document.getElementById('cancelForm').submit();
    }
}

// Close modal ketika klik di luar
document.getElementById('cashModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCashModal();
    }
});
</script>
@endpush