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
                <p class="text-white font-bold text-2xl">{{ $pemesanan->pembayaran->metode_online }}</p>
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
                <p class="text-red-300 text-xs mt-2">QR Code akan expired</p>
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
                    <li>1. Buka aplikasi <span class="text-[#FFA500] font-bold">{{ $pemesanan->pembayaran->metode_online }}</span></li>
                    <li>2. Pilih menu "Scan QR"</li>
                    <li>3. Scan QR Code di atas</li>
                    <li>4. Periksa nominal: <strong class="text-[#FFA500]">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</strong></li>
                    <li>5. Konfirmasi pembayaran</li>
                    <li>6. Tunggu verifikasi admin (1-10 menit)</li>
                </ol>
            </div>

            <!-- Status -->
            <div id="statusBox" class="bg-yellow-900/20 border border-yellow-600/40 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <div class="animate-pulse">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    </div>
                    <div class="flex-1">
                        <p class="text-yellow-400 font-bold text-sm">Menunggu Konfirmasi Admin...</p>
                        <p class="text-yellow-200/90 text-xs mt-1">Jangan tutup halaman ini. Refresh otomatis setiap 3 detik.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Back -->
        <div class="text-center mt-6">
            <a href="{{ route('booking.success', $pemesanan->pemesanan_id) }}" class="text-gray-400 hover:text-white text-sm">
                ← Kembali ke Detail Pemesanan
            </a>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
// Generate QR Code
const qrData = `{{ $pemesanan->kode_transaksi }}|{{ $pemesanan->jadwal->film->judul }}|{{ $pemesanan->total_bayar }}|{{ $pemesanan->pembayaran->metode_online }}`;
new QRCode(document.getElementById('qrcode'), {
    text: qrData,
    width: 250,
    height: 250
});

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
        alert('Waktu habis! QR Code expired.');
        window.location.href = '{{ route("payment.show", $pemesanan->pemesanan_id) }}';
    }
    timeLeft--;
}, 1000);

// Polling status setiap 3 detik
const statusInterval = setInterval(() => {
    fetch('{{ route("payment.checkStatus", $pemesanan->pemesanan_id) }}')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'approved') {
                clearInterval(timerInterval);
                clearInterval(statusInterval);
                window.location.href = data.redirect;
            } else if (data.status === 'rejected') {
                clearInterval(timerInterval);
                clearInterval(statusInterval);
                alert(data.message);
                window.location.href = data.redirect;
            }
        });
}, 3000);
</script>
@endsection