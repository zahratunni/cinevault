@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-3xl mx-auto px-4 md:px-6">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-500 rounded-full mb-4 animate-pulse">
                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Menunggu Pembayaran</h1>
            <p class="text-gray-400 text-sm">Scan QR Code dan konfirmasi pembayaran</p>
        </div>

        <div class="bg-[#1a1a1a] rounded-2xl shadow-2xl border border-gray-800 p-6 md:p-8">
            
            <!-- Logo Metode -->
            <div class="text-center mb-6">
                <p class="text-gray-400 text-sm mb-2">Metode Pembayaran</p>
                <p class="text-white font-bold text-2xl">{{ $pemesanan->pembayaran->metode_online ?? 'E-Wallet/Transfer Bank' }}</p>
            </div>

            <!-- QR Code -->
            <div class="flex justify-center mb-6">
                <div class="bg-white p-6 rounded-2xl inline-block">
                    <div id="qrcode"></div>
                </div>
            </div>

            <!-- Timer -->
            <div class="bg-red-900/20 border-2 border-red-500 rounded-xl p-4 mb-6 text-center">
                <p class="text-red-400 text-sm mb-2">Waktu tersisa:</p>
                <div id="timer" class="text-4xl font-black text-red-500">
                    <span id="minutes">05</span>:<span id="seconds">00</span>
                </div>
                <p class="text-red-300 text-xs mt-2">Selesaikan pembayaran sebelum waktu habis</p>
            </div>

            <!-- Instruksi -->
            <div class="bg-gray-900 rounded-xl p-5 mb-6">
                <p class="text-white font-bold mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#FFA500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Cara Pembayaran:
                </p>
                <ol class="text-gray-300 text-sm space-y-2">
                    <li>1. Buka aplikasi <span class="text-[#FFA500] font-bold">{{ $pemesanan->pembayaran->metode_online ?? 'E-Wallet/Mobile Banking' }}</span></li>
                    <li>2. Pilih menu "Scan QR" atau "Transfer"</li>
                    <li>3. Scan QR Code di atas</li>
                    <li>4. Periksa nominal: <strong class="text-[#FFA500]">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</strong></li>
                    <li>5. Konfirmasi pembayaran</li>
                    <li>6. <strong class="text-[#FFA500]">Klik tombol "Sudah Transfer" di bawah</strong></li>
                </ol>
            </div>

            <!-- Status & Upload Section -->
            <div id="statusSection">
                
                @if($pemesanan->pembayaran->status_pembayaran === 'Lunas')
                    <!-- Status: Lunas -->
                    <div class="bg-green-900/20 border-2 border-green-500 rounded-xl p-5 text-center">
                        <i class="fas fa-check-circle text-green-500 text-5xl mb-3"></i>
                        <p class="text-green-400 font-bold text-xl mb-2">Pembayaran Berhasil!</p>
                        <p class="text-green-200 text-sm">Tiket Anda sudah dikonfirmasi</p>
                        <a href="{{ route('payment.success', $pemesanan->kode_transaksi) }}" 
                           class="inline-block mt-4 bg-green-500 hover:bg-green-600 text-black font-bold py-3 px-6 rounded-lg">
                            Lihat E-Ticket
                        </a>
                    </div>

                @elseif($pemesanan->pembayaran->status_pembayaran === 'Gagal')
                    <!-- Status: Ditolak -->
                    <div class="bg-red-900/20 border-2 border-red-500 rounded-xl p-5">
                        <div class="flex items-start gap-4">
                            <i class="fas fa-times-circle text-red-500 text-4xl flex-shrink-0"></i>
                            <div class="flex-1">
                                <p class="text-red-400 font-bold text-lg mb-2">Pembayaran Ditolak</p>
                                <p class="text-red-200 text-sm mb-3">{{ $pemesanan->pembayaran->rejection_reason ?? 'Bukti pembayaran tidak valid' }}</p>
                                <button onclick="location.reload()" 
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                    Upload Ulang Bukti
                                </button>
                            </div>
                        </div>
                    </div>

                @elseif($pemesanan->pembayaran->bukti_pembayaran)
                    <!-- Status: Sudah Upload, Menunggu Verifikasi -->
                    <div class="space-y-4">
                        <!-- Preview Bukti -->
                        <div class="bg-gray-900 rounded-xl p-5">
                            <p class="text-white font-bold mb-3">Bukti Pembayaran Anda:</p>
                            <img src="{{ asset('storage/' . $pemesanan->pembayaran->bukti_pembayaran) }}" 
                                 alt="Bukti Pembayaran" 
                                 class="w-full rounded-lg border-2 border-green-500 cursor-pointer"
                                 onclick="window.open(this.src, '_blank')">
                            <p class="text-xs text-gray-400 mt-2 text-center">
                                <i class="fas fa-search-plus mr-1"></i>
                                Klik gambar untuk memperbesar
                            </p>
                        </div>

                        <!-- Status Menunggu -->
                        <div class="bg-yellow-900/20 border-2 border-yellow-600 rounded-xl p-5">
                            <div class="flex items-center gap-4">
                                <div class="animate-pulse">
                                    <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-yellow-400 font-bold text-base">Menunggu Verifikasi Kasir...</p>
                                    <p class="text-yellow-200 text-sm mt-1">Kasir akan memverifikasi pembayaran Anda (biasanya 1-10 menit)</p>
                                    <p class="text-yellow-300 text-xs mt-2">
                                        <i class="fas fa-sync-alt mr-1"></i>
                                        Refresh otomatis setiap 3 detik
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Ulang -->
                        <button onclick="showUploadForm()" 
                                class="w-full bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 rounded-lg text-sm">
                            <i class="fas fa-redo mr-2"></i>
                            Upload Ulang Bukti
                        </button>
                    </div>

                @else
                    <!-- Tombol: Sudah Transfer -->
                    <button id="btnSudahTransfer" 
                            onclick="showUploadForm()" 
                            class="w-full bg-[#FFA500] hover:bg-[#ff8c00] text-black font-bold text-lg py-4 rounded-xl transition-all shadow-lg hover:scale-[1.02] flex items-center justify-center gap-3">
                        <i class="fas fa-check-circle text-2xl"></i>
                        <span>Sudah Transfer, Upload Bukti</span>
                    </button>

                    <p class="text-center text-xs text-gray-500 mt-3">
                        Klik tombol di atas setelah Anda menyelesaikan pembayaran
                    </p>
                @endif
            </div>

        </div>

        <!-- Back -->
        <div class="text-center mt-6">
            <a href="{{ route('booking.success', $pemesanan->pemesanan_id) }}" 
               class="text-gray-400 hover:text-white text-sm">
                ← Kembali ke Detail Pemesanan
            </a>
        </div>

    </div>
</div>

<!-- Modal Upload Bukti -->
<div id="uploadModal" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4">
    <div class="bg-[#1a1a1a] rounded-2xl max-w-lg w-full border-2 border-[#FFA500]">
        <div class="bg-[#FFA500] px-6 py-4 flex justify-between items-center rounded-t-2xl">
            <h3 class="text-xl font-bold text-black">Upload Bukti Pembayaran</h3>
            <button onclick="hideUploadForm()" class="text-black hover:text-white text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('payment.uploadBukti', $pemesanan->pembayaran->pembayaran_id) }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="p-6 space-y-5">
            @csrf

            <!-- Info -->
            <div class="bg-blue-900/20 border border-blue-600/40 rounded-lg p-4 text-sm text-blue-300">
                <i class="fas fa-info-circle mr-2"></i>
                Upload screenshot/foto bukti transfer Anda. Pastikan nominal dan tanggal terlihat jelas!
            </div>

            <!-- File Input -->
            <div>
                <label class="block text-white font-semibold mb-3">
                    Pilih File Bukti <span class="text-red-500">*</span>
                </label>
                <input type="file" 
                       name="bukti_pembayaran" 
                       id="bukti_pembayaran"
                       accept="image/*"
                       required
                       onchange="previewImage(event)"
                       class="block w-full text-sm text-gray-300 
                              file:mr-4 file:py-3 file:px-4 
                              file:rounded-lg file:border-0 
                              file:text-sm file:font-semibold 
                              file:bg-[#FFA500] file:text-black 
                              hover:file:bg-[#ff8c00] 
                              cursor-pointer bg-gray-800 rounded-lg border-2 border-gray-700
                              @error('bukti_pembayaran') border-red-500 @enderror">
                
                @error('bukti_pembayaran')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
                
                <p class="text-xs text-gray-500 mt-2">
                    Format: JPG, PNG, JPEG • Max 2MB
                </p>
            </div>

            <!-- Preview -->
            <div id="imagePreview" class="hidden">
                <p class="text-sm text-gray-400 mb-2">Preview:</p>
                <img id="preview" src="" alt="Preview" class="w-full rounded-lg border-2 border-[#FFA500]">
            </div>

            <!-- Nominal Konfirmasi -->
            <div class="bg-gray-900 rounded-lg p-4">
                <p class="text-gray-400 text-sm mb-1">Total yang harus dibayar:</p>
                <p class="text-3xl font-bold text-[#FFA500]">
                    Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                </p>
            </div>

            <!-- Submit -->
            <button type="submit" 
                    class="w-full bg-[#FFA500] hover:bg-[#ff8c00] text-black font-bold py-4 rounded-xl transition-all">
                <i class="fas fa-upload mr-2"></i>
                Kirim Bukti Pembayaran
            </button>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
// Generate QR Code
const qrData = `CINEVAULT|{{ $pemesanan->kode_transaksi }}|Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}`;
new QRCode(document.getElementById('qrcode'), {
    text: qrData,
    width: 250,
    height: 250
});

// Show/Hide Upload Form
function showUploadForm() {
    document.getElementById('uploadModal').classList.remove('hidden');
}

function hideUploadForm() {
    document.getElementById('uploadModal').classList.add('hidden');
}

// Preview Image
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

// Timer (5 menit)
let timeLeft = 300;
const timerInterval = setInterval(() => {
    const m = Math.floor(timeLeft / 60);
    const s = timeLeft % 60;
    document.getElementById('minutes').textContent = String(m).padStart(2, '0');
    document.getElementById('seconds').textContent = String(s).padStart(2, '0');
    
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        clearInterval(statusInterval);
        alert('Waktu habis! Silakan buat pesanan baru.');
        window.location.href = '{{ route("home") }}';
    }
    timeLeft--;
}, 1000);

// Polling status setiap 3 detik (hanya jika sudah upload bukti)
@if($pemesanan->pembayaran->bukti_pembayaran && $pemesanan->pembayaran->status_pembayaran === 'Pending')
const statusInterval = setInterval(() => {
    fetch('{{ route("payment.checkStatus", $pemesanan->pemesanan_id) }}')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' || data.status === 'approved') {
                clearInterval(timerInterval);
                clearInterval(statusInterval);
                window.location.href = data.redirect || '{{ route("payment.success", $pemesanan->kode_transaksi) }}';
            } else if (data.status === 'failed' || data.status === 'rejected') {
                clearInterval(timerInterval);
                clearInterval(statusInterval);
                location.reload();
            }
        })
        .catch(err => console.error('Polling error:', err));
}, 3000);
@endif
</script>
@endsection