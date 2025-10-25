@extends('layouts.app')

@section('content')
{{-- Mengubah bg-[#1A202C] menjadi bg-white untuk latar belakang putih --}}
<div class="bg-white min-h-screen flex items-center justify-center"> 
    <div class="text-center">
        <!-- Loading Animation -->
        <div class="mb-6">
            {{-- Warna spinner tetap biru utama dari halaman home (#007BFF) --}}
            <svg class="animate-spin h-16 w-16 text-[#007BFF] mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        {{-- Mengubah warna teks utama menjadi text-gray-800 atau text-black agar terlihat jelas di latar belakang putih --}}
        <h2 class="text-gray-800 text-2xl font-bold mb-2" id="loadingText">Memproses Pembayaran...</h2>
        {{-- Mengubah text-gray-300 menjadi text-gray-600 atau text-gray-700 agar kontras lebih baik di latar belakang putih --}}
        <p class="text-gray-600 text-sm" id="loadingSubtext">Mohon tunggu sebentar</p>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    // Auto-trigger popup Midtrans
    window.onload = function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                // 🔥 Update text loading saat redirect
                document.getElementById('loadingText').textContent = 'Pembayaran Berhasil!';
                document.getElementById('loadingSubtext').textContent = 'Redirecting ke invoice...';
                
                window.location.href = '/test-midtrans-success/{{ $pemesanan->pemesanan_id }}';
            },
            onPending: function(result) {
                // 🔥 Update text loading saat redirect
                document.getElementById('loadingText').textContent = 'Menunggu Konfirmasi Pembayaran';
                document.getElementById('loadingSubtext').textContent = 'Redirecting...';
                
                window.location.href = '/test-midtrans-success/{{ $pemesanan->pemesanan_id }}';
            },
            onError: function(result) {
                alert('Pembayaran gagal: ' + (result.status_message || 'Terjadi kesalahan'));
                window.location.href = '{{ route("payment.show", $pemesanan->pemesanan_id) }}';
            },
            onClose: function() {
                window.location.href = '{{ route("payment.show", $pemesanan->pemesanan_id) }}';
            }
        });
    };
</script>
@endsection