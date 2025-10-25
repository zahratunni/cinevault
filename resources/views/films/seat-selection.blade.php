@extends('layouts.app')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen text-[#2C3E50] py-12">
    <div class="max-w-7xl mx-auto px-8">

        <!-- Header Info Film & Jadwal -->
        <div class="mb-8">
            <a href="{{ route('film.show', $jadwal->film->film_id) }}" class="text-gray-600 hover:text-[#007BFF] mb-4 inline-block transition-colors duration-200">
                ← Kembali ke Detail Film
            </a>
        </div>

        <!-- Layout Dua Kolom -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- BAGIAN KIRI: KURSI BIOSKOP -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <!-- Layar -->
                <div class="mb-12">
                    <div class="bg-gradient-to-b from-gray-300 to-transparent h-3 rounded-t-3xl mb-2"></div>
                    <p class="text-center text-gray-500 text-sm">LAYAR</p>
                </div>

                <!-- Seat Map -->
                <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->jadwal_id }}">
                    
                    <div class="mb-8">
                        @foreach($kursisByBaris as $baris => $kursis)
                        <div class="flex items-center justify-center gap-2 mb-3">
                            <!-- Label Baris -->
                            <span class="text-gray-500 font-bold w-8 text-center">{{ $baris }}</span>
                            
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
                                            class="block w-10 h-10 rounded-lg cursor-pointer transition-all duration-200
                                                {{ in_array($kursi->kursi_id, $bookedKursiIds) 
                                                    ? 'bg-[#FFC107] border-2 border-[#FFC107] cursor-not-allowed text-[#2C3E50]'
                                                    : 'bg-gray-100 border-2 border-gray-300 hover:border-[#007BFF] text-gray-700' }}
                                                peer-checked:bg-[#007BFF] peer-checked:border-[#007BFF] peer-checked:scale-110 peer-checked:text-white
                                                flex items-center justify-center text-xs font-medium">
                                            {{ $kursi->nomor_kursi }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Label Baris Kanan -->
                            <span class="text-gray-500 font-bold w-8 text-center">{{ $baris }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Legend -->
                    <div class="flex justify-center gap-8 mb-8 mt-12">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gray-100 border-2 border-gray-300 rounded-lg"></div>
                            <span class="text-sm text-gray-600">Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-[#007BFF] border-2 border-[#007BFF] rounded-lg"></div>
                            <span class="text-sm text-gray-600">Dipilih</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-[#FFC107] border-2 border-[#FFC107] rounded-lg"></div>
                            <span class="text-sm text-gray-600">Terisi</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- BAGIAN KANAN: DETAIL FILM & CHECKOUT -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 lg:sticky lg:top-8 h-fit" id="filmDetail">
                <div class="flex flex-col items-center text-center">
                    <img src="{{ asset($jadwal->film->poster_url) }}" 
                        alt="{{ $jadwal->film->judul }}" 
                        class="w-40 h-56 object-cover rounded-xl mb-4 shadow-lg">
                    
                    <h1 class="text-2xl font-bold mb-1 text-[#2C3E50]">{{ $jadwal->film->judul }}</h1>
                    <div class="text-gray-600 space-y-1 mb-4">
                        <p>{{ $jadwal->studio->nama_studio }}</p>
                        <p>{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d F Y') }}</p>
                        <p>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</p>
                    </div>
                    <p class="text-[#007BFF] font-bold text-lg mb-6">
                        Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }} / kursi
                    </p>

                    <!-- DETAIL KURSI & TOTAL -->
                    <div id="kursiDetail" class="w-full bg-[#EBF7FF] rounded-xl p-4 mb-6 hidden border border-[#CCE5FF]">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm">Kursi dipilih:</span>
                                <span id="kursiDipilihText" class="font-bold text-[#2C3E50]">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm">Jumlah:</span>
                                <span id="jumlahKursiText" class="font-bold text-[#2C3E50]">0 kursi</span>
                            </div>
                            <div class="border-t border-gray-300 pt-2 mt-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-[#2C3E50] font-semibold">Total:</span>
                                    <span id="totalHargaText" class="font-bold text-[#007BFF] text-xl">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pesan Error (Untuk mengganti alert) -->
                    <div id="validationError" class="p-3 bg-red-100 text-red-700 rounded-lg text-sm mb-4 w-full hidden">
                        Silakan pilih minimal 1 kursi untuk melanjutkan pembayaran.
                    </div>

                    <!-- Tombol Pembayaran (SATU-SATUNYA TOMBOL) -->
                    <button type="button" 
                            id="btnPembayaran"
                            class="w-full bg-[#007BFF] hover:bg-[#0056B3] text-white font-bold px-8 py-4 rounded-xl transition-all shadow-md hover:shadow-xl transform hover:scale-105 
                                   disabled:opacity-50 disabled:cursor-not-allowed hidden">
                        Lanjutkan Pembayaran
                    </button>
                    
                    <!-- Info ketika belum pilih kursi -->
                    <p id="infoPilihKursi" class="text-gray-500 text-sm mt-4">
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
    const validationError = document.getElementById('validationError'); // Ambil elemen pesan error

    function updateCheckout() {
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
        const jumlahKursi = selectedCheckboxes.length;
        
        // Sembunyikan pesan error setiap kali ada perubahan
        validationError.classList.add('hidden');

        if (jumlahKursi > 0) {
            kursiDetail.classList.remove('hidden');
            btnPembayaran.classList.remove('hidden');
            infoPilihKursi.classList.add('hidden');
            
            // PASTIKAN TOMBOL TIDAK DI-DISABLED JIKA ADA PILIHAN
            btnPembayaran.disabled = false;

            const kursiNames = selectedCheckboxes.map(cb => {
                // Ambil label kursi dari parent element
                const label = document.querySelector(`label[for="${cb.id}"]`);
                return label ? label.textContent.trim() : 'Kursi';
            });

            kursiDipilihText.textContent = kursiNames.join(', ');
            jumlahKursiText.textContent = jumlahKursi + ' kursi';
            
            const total = jumlahKursi * hargaPerKursi;
            // Gunakan Intl.NumberFormat untuk format Rupiah yang benar
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            totalHargaText.textContent = formatter.format(total);
        } else {
            kursiDetail.classList.add('hidden');
            btnPembayaran.classList.add('hidden');
            infoPilihKursi.classList.remove('hidden');
            // PASTIKAN TOMBOL DI-DISABLED JIKA TIDAK ADA PILIHAN
            btnPembayaran.disabled = true;
        }
    }

    checkboxes.forEach(checkbox => {
        // Tambahkan event listener untuk update UI saat kursi dipilih/dibatalkan
        checkbox.addEventListener('change', updateCheckout);
    });

    updateCheckout(); // Panggil saat awal untuk inisialisasi status tombol

    // Event Listener untuk Tombol Pembayaran
    btnPembayaran.addEventListener('click', function(e) {
        // e.preventDefault(); tidak lagi diperlukan di sini karena tombol bukan type="submit"
        
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
        
        if (selectedCheckboxes.length === 0) {
            // Tampilkan pesan error di UI (Mengganti alert)
            validationError.classList.remove('hidden');
            return;
        }
        
        // Disabling tombol dan ganti teks untuk feedback ke user
        this.disabled = true; 
        this.textContent = 'Memproses...';
        
        // PENTING: Memicu form submission, ini akan menuju ke BookingController@store
        bookingForm.submit();
    });
});
</script>
@endsection
