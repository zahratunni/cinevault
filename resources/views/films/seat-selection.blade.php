@extends('layouts.app')

@section('content')
<div class="bg-[#1a1a1a] min-h-screen text-white py-8">
    <div class="max-w-7xl mx-auto px-8">

        <!-- Header Info Film & Jadwal -->
        <div class="mb-8">
            <a href="{{ route('film.show', $jadwal->film->film_id) }}" class="text-gray-400 hover:text-white mb-4 inline-block">
                ← Kembali ke Detail Film
            </a>
        </div>

        <!-- Layout Dua Kolom -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- BAGIAN KIRI: KURSI BIOSKOP -->
            <div class="lg:col-span-2 bg-[#2a2a2a] rounded-2xl p-6 border border-gray-800">
                <!-- Layar -->
                <div class="mb-12">
                    <div class="bg-gradient-to-b from-gray-700 to-transparent h-3 rounded-t-3xl mb-2"></div>
                    <p class="text-center text-gray-400 text-sm">LAYAR</p>
                </div>

                <!-- Seat Map -->
                <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->jadwal_id }}">
                    
                    <div class="mb-8">
                        @foreach($kursisByBaris as $baris => $kursis)
                        <div class="flex items-center justify-center gap-2 mb-3">
                            <!-- Label Baris -->
                            <span class="text-gray-400 font-bold w-8 text-center">{{ $baris }}</span>
                            
                            <!-- Kursi -->
                            <div class="flex gap-2">
                                @foreach($kursis as $index => $kursi)
                                    @if($index == ceil(count($kursis)/2))
                                        <!-- Lorong tengah -->
                                        <div class="w-8"></div>
                                    @endif
                                    <div class="relative">
                                        <input type="checkbox" 
                                            name="kursi_ids[]" 
                                            value="{{ $kursi->kursi_id }}" 
                                            id="kursi_{{ $kursi->kursi_id }}"
                                            class="peer hidden kursi-checkbox"
                                            {{ in_array($kursi->kursi_id, $bookedKursiIds) ? 'disabled' : '' }}>
                                        
                                        <label for="kursi_{{ $kursi->kursi_id }}" 
                                            class="block w-10 h-10 rounded-lg cursor-pointer transition-all
                                                {{ in_array($kursi->kursi_id, $bookedKursiIds) 
                                                    ? 'bg-red-900 border-2 border-red-700 cursor-not-allowed' 
                                                    : 'bg-gray-700 border-2 border-gray-600 hover:border-[#FF9500]' }}
                                                peer-checked:bg-[#FF9500] peer-checked:border-[#FF9500] peer-checked:scale-110
                                                flex items-center justify-center text-xs font-medium">
                                            {{ $kursi->nomor_kursi }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Label Baris Kanan -->
                            <span class="text-gray-400 font-bold w-8 text-center">{{ $baris }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Legend -->
                    <div class="flex justify-center gap-8 mb-8">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gray-700 border-2 border-gray-600 rounded-lg"></div>
                            <span class="text-sm text-gray-300">Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-[#FF9500] border-2 border-[#FF9500] rounded-lg"></div>
                            <span class="text-sm text-gray-300">Dipilih</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-red-900 border-2 border-red-700 rounded-lg"></div>
                            <span class="text-sm text-gray-300">Terisi</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- BAGIAN KANAN: DETAIL FILM -->
            <div class="bg-[#2a2a2a] rounded-2xl p-6 border border-gray-800 lg:sticky lg:top-8 h-fit" id="filmDetail">
                <div class="flex flex-col items-center text-center">
                    <img src="{{ asset($jadwal->film->poster_url) }}" 
                        alt="{{ $jadwal->film->judul }}" 
                        class="w-40 h-56 object-cover rounded-xl mb-4 shadow-lg">
                    
                    <h1 class="text-2xl font-bold mb-1">{{ $jadwal->film->judul }}</h1>
                    <div class="text-gray-300 space-y-1 mb-4">
                        <p>{{ $jadwal->studio->nama_studio }}</p>
                        <p>{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d F Y') }}</p>
                        <p>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</p>
                    </div>
                    <p class="text-[#FF9500] font-bold text-lg mb-6">
                        Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }} / kursi
                    </p>

                    <!-- DETAIL KURSI & TOTAL -->
                    <div id="kursiDetail" class="w-full bg-[#1a1a1a] rounded-xl p-4 mb-6 hidden">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-sm">Kursi dipilih:</span>
                                <span id="kursiDipilihText" class="font-bold text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-sm">Jumlah:</span>
                                <span id="jumlahKursiText" class="font-bold text-white">0 kursi</span>
                            </div>
                            <div class="border-t border-gray-700 pt-2 mt-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-white font-semibold">Total:</span>
                                    <span id="totalHargaText" class="font-bold text-[#FF9500] text-xl">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Pembayaran (SATU-SATUNYA TOMBOL) -->
                    <button type="button" 
                            id="btnPembayaran"
                            class="w-full bg-[#FF9500] hover:bg-[#FF8000] text-white font-bold px-8 py-4 rounded-xl transition-all shadow-md hover:shadow-xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed hidden">
                        Lanjutkan Pembayaran
                    </button>
                    
                    <!-- Info ketika belum pilih kursi -->
                    <p id="infoPilihKursi" class="text-gray-400 text-sm mt-4">
                        Pilih kursi terlebih dahulu
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hargaPerKursi = {{ $jadwal->harga_reguler }};
    const checkboxes = document.querySelectorAll('.kursi-checkbox');
    const bookingForm = document.getElementById('bookingForm');

    // Elemen sidebar kanan
    const kursiDetail = document.getElementById('kursiDetail');
    const kursiDipilihText = document.getElementById('kursiDipilihText');
    const jumlahKursiText = document.getElementById('jumlahKursiText');
    const totalHargaText = document.getElementById('totalHargaText');
    const btnPembayaran = document.getElementById('btnPembayaran');
    const infoPilihKursi = document.getElementById('infoPilihKursi');

    // --- Fungsi update tampilan saat klik kursi ---
    function updateCheckout() {
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
        const jumlahKursi = selectedCheckboxes.length;

        if (jumlahKursi > 0) {
            // Tampilkan detail & tombol
            kursiDetail.classList.remove('hidden');
            btnPembayaran.classList.remove('hidden');
            infoPilihKursi.classList.add('hidden');

            // Ambil nama kursi (A5, B3, dst)
            const kursiNames = selectedCheckboxes.map(cb => {
                const baris = cb.closest('.flex.items-center').querySelector('.text-gray-400').textContent.trim();
                const label = document.querySelector(`label[for="${cb.id}"]`);
                const nomor = label.textContent.trim();
                return baris + nomor;
            });

            // Update teks
            kursiDipilihText.textContent = kursiNames.join(', ');
            jumlahKursiText.textContent = jumlahKursi + ' kursi';
            
            const total = jumlahKursi * hargaPerKursi;
            totalHargaText.textContent = 'Rp ' + total.toLocaleString('id-ID');
        } else {
            // Sembunyikan detail & tombol
            kursiDetail.classList.add('hidden');
            btnPembayaran.classList.add('hidden');
            infoPilihKursi.classList.remove('hidden');
        }
    }

    // Jalankan update saat ada perubahan kursi
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCheckout);
    });

    // Tombol pembayaran - submit form
    btnPembayaran.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validasi kursi dipilih
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
        if (selectedCheckboxes.length === 0) {
            alert('Silakan pilih minimal 1 kursi!');
            return;
        }
        
        // Disable tombol & ubah teks
        btnPembayaran.disabled = true;
        btnPembayaran.textContent = 'Memproses...';
        
        // Submit form
        bookingForm.submit();
    });
});
</script>
@endsection