@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen pt-24 md:pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        
        <!-- Header -->
        <div class="text-center mb-8 md:mb-12">
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 md:mb-3">Pembayaran</h1>
            <p class="text-gray-400 text-sm md:text-lg">Pilih metode pembayaran dan selesaikan transaksi</p>
        </div>

        @if(session('error'))
        <div class="bg-red-900/20 border border-red-500 rounded-xl p-4 mb-6">
            <p class="text-red-400 text-sm">{{ session('error') }}</p>
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6 md:gap-8">
            
            <!-- Left: Payment Form -->
            <div class="bg-[#1a1a1a] rounded-xl md:rounded-2xl shadow-2xl border border-gray-800 p-6 md:p-8">
                <h2 class="text-lg md:text-xl font-bold text-white mb-6">Metode Pembayaran</h2>
                
                <form action="{{ route('payment.process', $pemesanan->pemesanan_id) }}" method="POST" id="paymentForm">
                    @csrf

                    <!-- Custom Dropdown -->
                    <div class="mb-8">
                        <label class="block text-white font-semibold mb-3">Pilih Metode Pembayaran</label>

                        <!-- Trigger -->
                        <div id="dropdownTrigger" 
                             class="flex justify-between items-center bg-gray-900 border border-gray-700 rounded-lg p-3 cursor-pointer hover:border-[#FFA500] transition">
                            <span id="selectedMethod" class="text-gray-400 text-sm">-- Pilih Metode Pembayaran --</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- Dropdown List -->
                        <div id="dropdownMenu" class="hidden mt-2 bg-gray-900 border border-gray-700 rounded-lg overflow-hidden shadow-lg">
                            <div class="p-2">
                                <p class="text-gray-500 text-xs px-2 mt-1 mb-1">E-Wallet</p>
                                @foreach (['DANA'=>'dana.jpg','OVO'=>'ovo.png','GoPay'=>'gopay.jpg'] as $name => $img)
                                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-800 cursor-pointer select-option" 
                                         data-value="{{ $name }}">
                                        <img src="{{ asset('payment/'.$img) }}" alt="{{ $name }}" class="w-8 h-8 rounded-md object-contain">
                                        <span class="text-white text-sm">{{ $name }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <hr class="border-gray-800">
                            <div class="p-2">
                                <p class="text-gray-500 text-xs px-2 mt-1 mb-1">Transfer Bank</p>
                                @foreach (['BCA'=>'bca.jpg','BRI'=>'logo-bri-biru.png','BNI'=>'bni.png','Mandiri'=>'mandiri.png'] as $name => $img)
                                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-800 cursor-pointer select-option" 
                                         data-value="{{ $name }}">
                                        <img src="{{ asset('payment/'.$img) }}" alt="{{ $name }}" class="w-8 h-8 rounded-md object-contain">
                                        <span class="text-white text-sm">{{ $name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" required>
                    </div>

                    <!-- ⚠️ PERINGATAN -->
                    <div class="bg-red-900/30 border-2 border-red-500 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-red-400 font-bold mb-2">PENTING SEBELUM BAYAR!</p>
                                <ul class="text-red-200 text-xs space-y-1">
                                    <li>• Pastikan metode pembayaran sudah dipilih</li>
                                    <li>• Scan QR atau transfer sesuai nominal tepat</li>
                                    <li>• <strong>Jangan tutup halaman setelah scan</strong></li>
                                    <li>• Klik tombol konfirmasi setelah bayar</li>
                                    <li>• Admin akan verifikasi dalam 1-10 menit</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ CHECKBOX KONFIRMASI -->
                    <div class="bg-gray-900 rounded-xl p-4 mb-6 space-y-3">
                        @foreach ([
                            'Saya akan melakukan pembayaran sesuai metode yang dipilih',
                            'Saya setuju tiket tidak dapat dibatalkan/di-refund',
                            'Data pemesanan sudah benar dan tidak dapat diubah'
                        ] as $i => $text)
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" id="checkbox{{ $i+1 }}" 
                                class="mt-1 w-5 h-5 rounded border-gray-600 text-[#FFA500] focus:ring-[#FFA500]" onchange="checkAll()">
                            <span class="text-gray-300 text-sm group-hover:text-white">{{ $text }}</span>
                        </label>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="btnSubmit" disabled 
                        class="w-full bg-gray-600 text-gray-400 font-bold py-4 rounded-xl transition-all shadow-lg cursor-not-allowed">
                        Lanjutkan Pembayaran
                    </button>
                    <p class="text-gray-500 text-xs text-center mt-3">
                        Dengan melanjutkan, Anda menyetujui syarat & ketentuan yang berlaku
                    </p>
                </form>
            </div>

            <!-- Right: Ringkasan Pesanan -->
            <div>
                <div class="bg-[#1a1a1a] rounded-xl md:rounded-2xl shadow-2xl border border-gray-800 p-6 md:p-8 mb-6">
                    <h2 class="text-lg md:text-xl font-bold text-white mb-6">Ringkasan Pesanan</h2>
                    <div class="flex gap-4 mb-6 pb-6 border-b border-gray-800">
                        <img src="{{ asset($pemesanan->jadwal->film->poster_url) }}" 
                             alt="{{ $pemesanan->jadwal->film->judul }}" 
                             class="w-20 h-28 object-cover rounded-lg border border-gray-700">
                        <div>
                            <h3 class="font-bold text-white mb-2">{{ $pemesanan->jadwal->film->judul }}</h3>
                            <p class="text-sm text-gray-400">{{ $pemesanan->jadwal->studio->nama_studio }}</p>
                            <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal_tayang)->format('d/m/Y') }} | {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_mulai)->format('H:i') }}</p>
                        </div>
                    </div>
                    <div class="mb-6 pb-6 border-b border-gray-800">
                        <p class="text-xs text-gray-500 mb-1">Kode Pemesanan</p>
                        <p class="text-lg font-bold text-[#FFA500]">{{ $pemesanan->kode_transaksi }}</p>
                    </div>
                    <div class="mb-6 pb-6 border-b border-gray-800">
                        <p class="text-xs text-gray-500 mb-2">Kursi</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($pemesanan->detailPemesanans as $detail)
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-sm font-semibold">
                                {{ $detail->kursi->kode_kursi }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">{{ $pemesanan->detailPemesanans->count() }} x Tiket</span>
                            <span class="text-white font-semibold">Rp {{ number_format($pemesanan->jadwal->harga_reguler, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-gray-800">
                            <span class="font-bold text-white">Total</span>
                            <span class="font-bold text-[#FFA500] text-xl">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-900/20 border border-blue-600/40 rounded-xl p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-blue-400 font-bold text-sm mb-1">Catatan:</p>
                            <p class="text-xs text-blue-200/90">Setelah melanjutkan, Anda akan diarahkan ke halaman instruksi pembayaran dengan QR Code. Admin akan mengkonfirmasi pembayaran dalam 1-10 menit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const trigger = document.getElementById('dropdownTrigger');
const menu = document.getElementById('dropdownMenu');
const hiddenInput = document.getElementById('metode_pembayaran');
const selectedText = document.getElementById('selectedMethod');

trigger.addEventListener('click', () => {
    menu.classList.toggle('hidden');
});

document.querySelectorAll('.select-option').forEach(opt => {
    opt.addEventListener('click', () => {
        const value = opt.getAttribute('data-value');
        hiddenInput.value = value;
        selectedText.innerHTML = `<img src="${opt.querySelector('img').src}" class='inline-block w-6 h-6 mr-2 rounded-md'>${value}`;
        menu.classList.add('hidden');
    });
});

function checkAll() {
    const cb1 = document.getElementById('checkbox1').checked;
    const cb2 = document.getElementById('checkbox2').checked;
    const cb3 = document.getElementById('checkbox3').checked;
    const btn = document.getElementById('btnSubmit');
    if (cb1 && cb2 && cb3) {
        btn.disabled = false;
        btn.classList.remove('bg-gray-600', 'text-gray-400', 'cursor-not-allowed');
        btn.classList.add('bg-[#FFA500]', 'hover:bg-[#FF8C00]', 'text-black');
    } else {
        btn.disabled = true;
        btn.classList.add('bg-gray-600', 'text-gray-400', 'cursor-not-allowed');
        btn.classList.remove('bg-[#FFA500]', 'hover:bg-[#FF8C00]', 'text-black');
    }
}
</script>
@endsection
