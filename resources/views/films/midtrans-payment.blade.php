@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-2xl mx-auto px-4 md:px-6">
        
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-3">Pembayaran Midtrans</h1>
            <p class="text-gray-400 text-sm md:text-lg">Klik tombol di bawah untuk melakukan pembayaran</p>
        </div>

        <div class="bg-[#1a1a1a] rounded-2xl shadow-2xl border border-gray-800 p-8 mb-6">
            
            <div class="mb-8 pb-8 border-b border-gray-800">
                <h2 class="text-xl font-bold text-white mb-4">Detail Pemesanan</h2>
                <div class="flex gap-4 mb-4">
                    <img src="{{ asset($pemesanan->jadwal->film->poster_url) }}" 
                         alt="{{ $pemesanan->jadwal->film->judul }}" 
                         class="w-20 h-28 object-cover rounded-lg border border-gray-700">
                    <div>
                        <h3 class="font-bold text-white mb-2">{{ $pemesanan->jadwal->film->judul }}</h3>
                        <p class="text-sm text-gray-400">{{ $pemesanan->jadwal->studio->nama_studio }}</p>
                        <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d M Y') }} | {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }}</p>
                        <p class="text-sm text-[#FFA500] font-semibold mt-1">{{ $pemesanan->kode_transaksi }}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-700">
                    <span class="text-gray-400">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-[#FFA500]">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg flex items-center justify-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <span>Bayar Sekarang</span>
            </button>

            <div class="mt-6 bg-blue-900/20 border border-blue-600/40 rounded-xl p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-blue-400 font-bold text-sm mb-1">Info:</p>
                        <ul class="text-xs text-blue-200/90 space-y-1">
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
               class="text-gray-400 hover:text-white text-sm">
                ← Kembali ke Pilihan Pembayaran
            </a>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = '{{ route("midtrans.finish", $pemesanan->pemesanan_id) }}';
            },
            onPending: function(result) {
                alert('Menunggu pembayaran Anda');
                window.location.href = '{{ route("midtrans.finish", $pemesanan->pemesanan_id) }}';
            },
            onError: function(result) {
                alert('Pembayaran gagal, silakan coba lagi');
                console.log(result);
            },
            onClose: function() {
                alert('Anda menutup popup pembayaran');
            }
        });
    });
</script>
@endsection