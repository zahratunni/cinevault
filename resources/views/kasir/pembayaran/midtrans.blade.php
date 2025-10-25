@extends('layouts.kasir')

@section('title', 'Pembayaran QRIS - Kasir')
@section('page-title', 'Pembayaran QRIS / E-Wallet')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <div class="text-center mb-6">
            <div class="inline-block bg-green-100 rounded-full p-4 mb-4">
                <span class="text-6xl">📱</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pembayaran QRIS / E-Wallet</h1>
            <p class="text-gray-600">Minta customer untuk scan QR code di bawah</p>
        </div>

        <!-- Info Pemesanan -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6 mb-6">
            <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                    <p class="text-sm opacity-75 mb-1">Kode Booking</p>
                    <p class="text-2xl font-bold font-mono">{{ $pemesanan->kode_transaksi }}</p>
                </div>
                <div>
                    <p class="text-sm opacity-75 mb-1">Total Pembayaran</p>
                    <p class="text-2xl font-bold">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Midtrans Snap Container -->
        <div class="bg-gray-50 rounded-xl p-6 mb-6">
            <div id="snap-container" class="min-h-[400px]"></div>
        </div>

        <!-- Status Indicator -->
        <div class="text-center mb-6">
            <div id="status-indicator" class="inline-flex items-center bg-yellow-100 text-yellow-800 px-6 py-3 rounded-full">
                <div class="animate-pulse mr-3">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                </div>
                <span class="font-semibold">Menunggu pembayaran customer...</span>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
            <p class="font-semibold text-blue-900 mb-2"><i class="fas fa-list mr-2"></i>Instruksi:</p>
            <ol class="list-decimal list-inside space-y-1 text-blue-800 text-sm">
                <li>Tunjukkan layar ini ke customer</li>
                <li>Customer scan QR code atau pilih metode pembayaran</li>
                <li>Setelah pembayaran berhasil, halaman akan otomatis berpindah</li>
                <li>Sistem akan mendeteksi pembayaran otomatis</li>
            </ol>
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('kasir.pembayaran.index', $pemesanan->pemesanan_id) }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali (Batalkan)
            </a>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
// Embed Midtrans Snap
snap.embed('{{ $snapToken }}', {
    embedId: 'snap-container',
    onSuccess: function(result) {
        console.log('Payment success:', result);
        updateStatusIndicator('success', '✅ Pembayaran berhasil! Membuka tiket...');
        
        // ✅ Redirect ke tiket dengan auto print
        setTimeout(() => {
            window.location.href = '{{ route("kasir.tiket.show", $pemesanan->pemesanan_id) }}' + '?auto_print=1';
        }, 1500);
    },
    onPending: function(result) {
        console.log('Payment pending:', result);
        updateStatusIndicator('pending', '⏳ Menunggu konfirmasi pembayaran...');
        showPendingInstructions();
    },
    onError: function(result) {
        console.error('Payment error:', result);
        updateStatusIndicator('error', '❌ Pembayaran gagal. Silakan coba lagi.');
    }
});

// Update status indicator
function updateStatusIndicator(status, message) {
    const indicator = document.getElementById('status-indicator');
    indicator.className = 'inline-flex items-center px-6 py-3 rounded-full';
    
    if (status === 'success') {
        indicator.classList.add('bg-green-100', 'text-green-800');
        indicator.innerHTML = `
            <i class="fas fa-check-circle mr-3 text-green-500"></i>
            <span class="font-semibold">${message}</span>
        `;
    } else if (status === 'error') {
        indicator.classList.add('bg-red-100', 'text-red-800');
        indicator.innerHTML = `
            <i class="fas fa-times-circle mr-3 text-red-500"></i>
            <span class="font-semibold">${message}</span>
        `;
    } else if (status === 'pending') {
        indicator.classList.add('bg-blue-100', 'text-blue-800');
        indicator.innerHTML = `
            <div class="animate-pulse mr-3">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
            </div>
            <span class="font-semibold">${message}</span>
        `;
    }
}

// Show pending instructions untuk VA Transfer
function showPendingInstructions() {
    const instructionsDiv = document.createElement('div');
    instructionsDiv.className = 'bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mt-4';
    instructionsDiv.innerHTML = `
        <p class="font-semibold text-yellow-900 mb-2">
            <i class="fas fa-info-circle mr-2"></i>Menunggu Transfer Bank
        </p>
        <ul class="list-disc list-inside space-y-1 text-yellow-800 text-sm mb-4">
            <li>Customer telah mendapat nomor Virtual Account</li>
            <li>Minta customer untuk transfer sesuai nominal</li>
            <li>Setelah transfer berhasil, tiket akan otomatis tersedia</li>
        </ul>
        <a href="{{ route('kasir.dashboard') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
            <i class="fas fa-home mr-2"></i>Kembali ke Dashboard
        </a>
    `;
    
    document.querySelector('.bg-blue-50').after(instructionsDiv);
}
</script>
@endsection