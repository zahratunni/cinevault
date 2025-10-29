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
                
                <!-- ✅ Bayar Tunai (Pakai Modal Baru) -->
                <button onclick="openModalTunai()" class="w-full bg-orange-600 text-white py-3 rounded-lg hover:bg-orange-700 font-semibold transition">
                    <i class="fas fa-money-bill-wave mr-2"></i>Bayar Tunai
                </button>

                <!-- Bayar Online (Midtrans) -->
                <a href="{{ route('kasir.midtrans.create', $pemesanan->pemesanan_id) }}" 
                   class="block w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold text-center transition">
                    <i class="fas fa-qrcode mr-2"></i>Bayar Online (QRIS/E-Wallet)
                </a>

                <!-- ⭐ Manual Callback untuk Testing -->
                @if($pemesanan->pembayaran && $pemesanan->pembayaran->transaction_id)
                <div class="border-t pt-3 mt-3">
                    <p class="text-sm text-gray-600 mb-2 text-center font-medium">
                        <i class="fas fa-info-circle mr-1"></i>Setelah customer bayar:
                    </p>
                    <a href="{{ route('kasir.midtrans.manualCallback', $pemesanan->pemesanan_id) }}" 
                       class="block w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 font-semibold text-center transition shadow-md">
                        <i class="fas fa-check-circle mr-2"></i>Konfirmasi Pembayaran Online
                    </a>
                    <p class="text-xs text-gray-500 text-center mt-2">
                        Klik setelah customer selesai scan QRIS/bayar via e-wallet
                    </p>
                </div>
                @endif

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

<!-- ✅ Modal Pembayaran Tunai - DESIGN BARU -->
<div id="modalTunai" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <form action="{{ route('kasir.pembayaran.confirmTunai', $pemesanan->pemesanan_id) }}" method="POST" id="formTunai">
            @csrf
            
            <!-- Modal Header -->
            <div class="bg-orange-500 text-white px-6 py-4 rounded-t-xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-bold">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        Pembayaran Tunai
                    </h3>
                    <button type="button" onclick="closeModalTunai()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Total Bayar -->
                <div class="mb-6 p-4 bg-green-50 border-2 border-green-300 rounded-lg text-center">
                    <p class="text-sm text-gray-600 mb-1">Total yang harus dibayar:</p>
                    <p class="text-3xl font-bold text-green-700">
                        Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Input Nominal -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nominal Dibayar <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="nominal_dibayar" 
                           id="nominal_dibayar"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:ring focus:ring-orange-200 text-xl font-bold text-center @error('nominal_dibayar') border-red-500 @enderror" 
                           placeholder="0"
                           value="{{ old('nominal_dibayar', $pemesanan->total_bayar) }}"
                           min="{{ $pemesanan->total_bayar }}"
                           required
                           oninput="hitungKembalian()"
                           autofocus>
                    @error('nominal_dibayar')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tampilan Kembalian -->
                <div class="mb-6 p-4 bg-yellow-50 border-2 border-yellow-300 rounded-lg text-center" id="kembalianBox" style="display: none;">
                    <p class="text-sm text-gray-600 mb-1">Kembalian:</p>
                    <p class="text-4xl font-bold text-yellow-600" id="kembalianAmount">Rp 0</p>
                </div>

                <!-- Quick Amount Buttons -->
                <div class="mb-6">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Nominal Cepat:</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="setNominal({{ $pemesanan->total_bayar }})" 
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-semibold transition-colors">
                            Pas
                        </button>
                        <button type="button" onclick="setNominal(50000)" 
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-semibold transition-colors">
                            50rb
                        </button>
                        <button type="button" onclick="setNominal(100000)" 
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-semibold transition-colors">
                            100rb
                        </button>
                        <button type="button" onclick="setNominal(150000)" 
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-semibold transition-colors">
                            150rb
                        </button>
                        <button type="button" onclick="setNominal(200000)" 
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-semibold transition-colors">
                            200rb
                        </button>
                        <button type="button" onclick="setNominal(500000)" 
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-semibold transition-colors">
                            500rb
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" 
                        onclick="closeModalTunai()"
                        class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-check-circle mr-2"></i>
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
const totalBayar = {{ $pemesanan->total_bayar }};

// ✅ Modal Tunai Functions
function openModalTunai() {
    document.getElementById('modalTunai').classList.remove('hidden');
    document.getElementById('nominal_dibayar').focus();
    hitungKembalian();
}

function closeModalTunai() {
    document.getElementById('modalTunai').classList.add('hidden');
}

function setNominal(amount) {
    document.getElementById('nominal_dibayar').value = amount;
    hitungKembalian();
}

function hitungKembalian() {
    const nominalDibayar = parseInt(document.getElementById('nominal_dibayar').value) || 0;
    const kembalian = nominalDibayar - totalBayar;
    
    const kembalianBox = document.getElementById('kembalianBox');
    const kembalianAmount = document.getElementById('kembalianAmount');
    
    if (kembalian >= 0 && nominalDibayar >= totalBayar) {
        kembalianBox.style.display = 'block';
        kembalianAmount.textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
        kembalianAmount.classList.remove('text-red-600');
        kembalianAmount.classList.add('text-yellow-600');
    } else if (nominalDibayar > 0 && nominalDibayar < totalBayar) {
        kembalianBox.style.display = 'block';
        kembalianAmount.textContent = 'Kurang: Rp ' + Math.abs(kembalian).toLocaleString('id-ID');
        kembalianAmount.classList.remove('text-yellow-600');
        kembalianAmount.classList.add('text-red-600');
    } else {
        kembalianBox.style.display = 'none';
    }
}

// Close modal when clicking outside
document.getElementById('modalTunai')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalTunai();
    }
});

// Confirm Cancel
function confirmCancel() {
    if (confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?\n\nKursi akan dikembalikan ke pool.')) {
        document.getElementById('cancelForm').submit();
    }
}

// Auto open modal if validation error
document.addEventListener('DOMContentLoaded', function() {
    @error('nominal_dibayar')
        openModalTunai();
    @enderror
});
</script>
@endpush