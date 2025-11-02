@extends('layouts.app')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-2xl mx-auto px-4 md:px-6">
        
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-4xl font-extrabold text-[#2C3E50] mb-3">Pembayaran Midtrans</h1>
            <p class="text-gray-600 text-sm md:text-lg">Klik tombol di bawah untuk melakukan pembayaran</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-6">
            
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h2 class="text-xl font-bold text-[#2C3E50] mb-4">Detail Pemesanan</h2>
                <div class="flex gap-4 mb-4">
                    <img src="{{ asset($pemesanan->jadwal->film->poster_url) }}" 
                         alt="{{ $pemesanan->jadwal->film->judul }}" 
                         class="w-20 h-28 object-cover rounded-lg border border-gray-200 shadow-sm">
                    <div>
                        <h3 class="font-bold text-[#2C3E50] mb-2">{{ $pemesanan->jadwal->film->judul }}</h3>
                        <p class="text-sm text-gray-600">{{ $pemesanan->jadwal->studio->nama_studio }}</p>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d M Y') }} | {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }}</p>
                        <p class="text-sm text-[#007BFF] font-semibold mt-1">{{ $pemesanan->kode_transaksi }}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <span class="text-gray-600">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-[#007BFF]">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" 
                    class="w-full bg-gradient-to-r from-[#007BFF] to-[#0056B3] hover:opacity-90 text-white font-bold py-4 rounded-xl transition-all shadow-lg flex items-center justify-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <span>Bayar Sekarang</span>
            </button>

            <div class="mt-6 bg-[#EBF7FF] border border-[#CCE5FF] rounded-xl p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-[#007BFF] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-[#007BFF] font-bold text-sm mb-1">Info:</p>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>✓ Pembayaran aman & terenkripsi</li>
                            <li>✓ Tersedia 15+ metode pembayaran</li>
                            <li>✓ Verifikasi otomatis (langsung lunas)</li>
                            <li>✓ E-ticket langsung terbit</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('payment.show', $pemesanan->pemesanan_id) }}" 
               class="text-gray-600 hover:text-[#007BFF] text-sm transition-colors duration-200">
                ← Kembali ke Pilihan Pembayaran
            </a>
        </div>
    </div>
</div>

<!-- Midtrans Snap.js -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
console.log('========================================');
console.log('🔍 MIDTRANS PAYMENT PAGE LOADED');
console.log('Pemesanan ID:', '{{ $pemesanan->pemesanan_id }}');
console.log('Snap Token:', '{{ $snapToken }}');
console.log('========================================');

const payButton = document.getElementById('pay-button');

payButton.addEventListener('click', function () {
    console.log('🔘 PAY BUTTON CLICKED!');
    
    payButton.disabled = true;
    payButton.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 text-white inline-block" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Memproses...</span>';

    console.log('🚀 Opening Midtrans Snap...');

    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            console.log('========================================');
            console.log('✅ ✅ ✅ PAYMENT SUCCESS! ✅ ✅ ✅');
            console.log('Result:', result);
            console.log('========================================');
            
            alert('🎉 PEMBAYARAN BERHASIL!\n\nAnda akan diarahkan ke halaman invoice...');
            
            window.location.href = '/test-midtrans-success/{{ $pemesanan->pemesanan_id }}';
        },
        
        onPending: function(result) {
            console.log('========================================');
            console.log('⏰ PAYMENT PENDING');
            console.log('Result:', result);
            console.log('========================================');
            
            alert('⏰ Pembayaran pending.\nSilakan selesaikan pembayaran Anda.');
            window.location.href = '{{ route("profile.riwayat") }}';
        },
        
        onError: function(result) {
            console.log('========================================');
            console.error('❌ PAYMENT ERROR!');
            console.error('Result:', result);
            console.log('========================================');
            
            alert('❌ Pembayaran gagal!\nSilakan coba lagi.');
            window.location.href = '{{ route("profile.riwayat") }}';
        },
        
        onClose: function() {
            console.log('========================================');
            console.log('🚪 POPUP CLOSED BY USER - Redirect to Riwayat');
            console.log('========================================');
            
            // ⭐ REDIRECT KE RIWAYAT
            window.location.href = '{{ route("profile.riwayat") }}';
        }
    });
});
</script>
@endsection