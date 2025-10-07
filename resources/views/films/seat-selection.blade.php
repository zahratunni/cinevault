@extends('layouts.app')

@section('content')
<div class="bg-[#1a1a1a] min-h-screen text-white py-8">
    <div class="max-w-7xl mx-auto px-8">
        
        <!-- Header Info Film & Jadwal -->
        <div class="mb-8">
            <a href="{{ route('film.show', $jadwal->film->film_id) }}" class="text-gray-400 hover:text-white mb-4 inline-block">
                ← Kembali ke Detail Film
            </a>
            
            <div class="bg-[#2a2a2a] rounded-2xl p-6 border border-gray-800">
                <div class="flex items-start gap-6">
                    <img src="{{ asset($jadwal->film->poster_url) }}" 
                         alt="{{ $jadwal->film->judul }}" 
                         class="w-24 h-32 object-cover rounded-lg">
                    
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold mb-2">{{ $jadwal->film->judul }}</h1>
                        <div class="flex items-center gap-4 text-gray-300">
                            <span>{{ $jadwal->studio->nama_studio }}</span>
                            <span>•</span>
                            <span>{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d F Y') }}</span>
                            <span>•</span>
                            <span>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</span>
                            <span>•</span>
                            <span class="text-[#FF9500] font-bold">Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Layar -->
        <div class="mb-12">
            <div class="bg-gradient-to-b from-gray-700 to-transparent h-3 rounded-t-3xl mb-2"></div>
            <p class="text-center text-gray-400 text-sm">LAYAR</p>
        </div>

        <!-- Seat Map -->
        <form id="bookingForm" action="{{ route('booking.proses') }}" method="POST">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->jadwal_id }}">
            
            <div class="mb-8">
                @foreach($kursisByBaris as $baris => $kursis)
                <div class="flex items-center justify-center gap-2 mb-3">
                    <!-- Label Baris -->
                    <span class="text-gray-400 font-bold w-8 text-center">{{ $baris }}</span>
                    
                    <!-- Kursi -->
                    <div class="flex gap-2">
                        @foreach($kursis as $kursi)
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

            <!-- Summary & Checkout -->
            <div class="fixed bottom-0 left-0 right-0 bg-[#2a2a2a] border-t-2 border-[#FF9500] p-6 shadow-2xl" id="checkoutBar" style="display: none;">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Kursi dipilih: <span id="selectedSeats" class="text-white font-bold">-</span></p>
                        <p class="text-2xl font-bold text-[#FF9500]">Total: Rp <span id="totalPrice">0</span></p>
                    </div>
                    <button type="submit" 
                            class="bg-[#FF9500] hover:bg-[#FF8000] text-white font-bold px-12 py-4 rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                        Lanjutkan Pembayaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hargaPerKursi = {{ $jadwal->harga_reguler }};
    const checkboxes = document.querySelectorAll('.kursi-checkbox');
    const checkoutBar = document.getElementById('checkoutBar');
    const selectedSeatsDisplay = document.getElementById('selectedSeats');
    const totalPriceDisplay = document.getElementById('totalPrice');
    const bookingForm = document.getElementById('bookingForm');

    function updateCheckout() {
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
        const jumlahKursi = selectedCheckboxes.length;
        
        if (jumlahKursi > 0) {
            // Tampilkan bar
            checkoutBar.style.display = 'block';
            
            // Update jumlah kursi
            const kursiNames = selectedCheckboxes.map(cb => {
                const label = document.querySelector(`label[for="${cb.id}"]`);
                const baris = cb.closest('.flex.items-center').querySelector('.text-gray-400').textContent;
                const nomor = label.textContent.trim();
                return baris + nomor;
            }).join(', ');
            
            selectedSeatsDisplay.textContent = kursiNames + ' (' + jumlahKursi + ' kursi)';
            
            // Update total harga
            const total = jumlahKursi * hargaPerKursi;
            totalPriceDisplay.textContent = total.toLocaleString('id-ID');
        } else {
            checkoutBar.style.display = 'none';
        }
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCheckout);
    });

    // Validasi sebelum submit
    bookingForm.addEventListener('submit', function(e) {
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
        if (selectedCheckboxes.length === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal 1 kursi!');
        }
    });
});
</script>

@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif

@endsection