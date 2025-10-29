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
                    <p class="text-sm opacity-75 mb-1">Kode Transaksi</p>
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

        <!-- ⭐ TOMBOL MANUAL CALLBACK (untuk localhost testing) -->
        <div class="bg-purple-50 border-2 border-purple-300 rounded-xl p-6 mb-6">
            <div class="text-center mb-4">
                <i class="fas fa-info-circle text-purple-600 text-2xl mb-2"></i>
                <p class="font-semibold text-purple-900 mb-2">Setelah Customer Bayar:</p>
                <p class="text-sm text-purple-700 mb-4">
                    Klik tombol di bawah untuk konfirmasi status pembayaran dari Midtrans
                </p>
                <a href="{{ route('kasir.midtrans.manualCallback', $pemesanan->pemesanan_id) }}" 
                   class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-bold text-lg transition shadow-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    Konfirmasi Pembayaran
                </a>
            </div>
            <div class="text-xs text-purple-600 text-center space-y-1">
                <p><i class="fas fa-lightbulb mr-1"></i>Tombol ini untuk testing di localhost</p>
                <p>Di production dengan Ngrok/server online, pembayaran otomatis terdeteksi</p>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
            <p class="font-semibold text-blue-900 mb-2"><i class="fas fa-list mr-2"></i>Instruksi untuk Kasir:</p>
            <ol class="list-decimal list-inside space-y-1 text-blue-800 text-sm">
                <li>Tunjukkan layar ini ke customer</li>
                <li>Customer scan QR code atau pilih metode pembayaran (GoPay/ShopeePay/QRIS)</li>
                <li>Tunggu customer menyelesaikan pembayaran di aplikasi mereka</li>
                <li><strong>Setelah customer bayar sukses, klik tombol "Konfirmasi Pembayaran"</strong></li>
                <li>Sistem akan cek status ke Midtrans dan update otomatis</li>
            </ol>
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('kasir.kelola.detail', $pemesanan->pemesanan_id) }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Detail Pemesanan
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
        console.log('✅ Payment success callback:', result);
        updateStatusIndicator('success', '✅ Pembayaran berhasil!');
        
        // Show success message
        showSuccessMessage();
        
        // Note: Di localhost, callback tidak akan jalan otomatis
        // Kasir harus klik tombol "Konfirmasi Pembayaran" manual
    },
    onPending: function(result) {
        console.log('⏳ Payment pending:', result);
        updateStatusIndicator('pending', '⏳ Menunggu konfirmasi pembayaran...');
        showPendingInstructions();
    },
    onError: function(result) {
        console.error('❌ Payment error:', result);
        updateStatusIndicator('error', '❌ Pembayaran gagal. Silakan coba lagi.');
    },
    onClose: function() {
        console.log('ℹ️ Payment popup closed');
        // User menutup popup tanpa bayar
    }
});

// Update status indicator
function updateStatusIndicator(status, message) {
    const indicator = document.getElementById('status-indicator');
    indicator.className = 'inline-flex items-center px-6 py-3 rounded-full';
    
    if (status === 'success') {
        indicator.classList.add('bg-green-100', 'text-green-800');
        indicator.innerHTML = `
            <i class="fas fa-check-circle mr-3 text-green-500 text-xl"></i>
            <span class="font-semibold">${message}</span>
        `;
    } else if (status === 'error') {
        indicator.classList.add('bg-red-100', 'text-red-800');
        indicator.innerHTML = `
            <i class="fas fa-times-circle mr-3 text-red-500 text-xl"></i>
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

// Show success message (tapi tetep minta kasir klik konfirmasi)
function showSuccessMessage() {
    const existingMsg = document.getElementById('success-message');
    if (existingMsg) return; // Jangan duplikat

    const messageDiv = document.createElement('div');
    messageDiv.id = 'success-message';
    messageDiv.className = 'bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mt-4 animate-pulse';
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
            <div>
                <p class="font-bold text-green-900 mb-1">Customer sudah bayar!</p>
                <p class="text-sm text-green-700">
                    Klik tombol <strong>"Konfirmasi Pembayaran"</strong> di atas untuk update status
                </p>
            </div>
        </div>
    `;
    
    const purpleBox = document.querySelector('.bg-purple-50');
    purpleBox.classList.add('ring-4', 'ring-green-300', 'animate-pulse');
    purpleBox.before(messageDiv);
}

// Show pending instructions untuk VA Transfer
function showPendingInstructions() {
    const existingInstr = document.getElementById('pending-instructions');
    if (existingInstr) return;

    const instructionsDiv = document.createElement('div');
    instructionsDiv.id = 'pending-instructions';
    instructionsDiv.className = 'bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mt-4';
    instructionsDiv.innerHTML = `
        <p class="font-semibold text-yellow-900 mb-2">
            <i class="fas fa-info-circle mr-2"></i>Menunggu Transfer Bank
        </p>
        <ul class="list-disc list-inside space-y-1 text-yellow-800 text-sm mb-4">
            <li>Customer telah mendapat nomor Virtual Account</li>
            <li>Minta customer untuk transfer sesuai nominal</li>
            <li>Setelah transfer berhasil, klik tombol "Konfirmasi Pembayaran"</li>
        </ul>
    `;
    
    document.querySelector('.bg-blue-50').after(instructionsDiv);
}
</script>
@endsection