@extends('layouts.kasir')

@section('title', 'Pemesanan Tiket Offline - Kasir')
@section('page-title', 'Pemesanan Tiket Offline')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">
            <i class="fas fa-ticket-alt mr-2 text-blue-600"></i> Formulir Pemesanan Tiket Offline
        </h2>

        <form id="pemesananForm" method="POST" action="{{ route('kasir.pemesanan.store') }}" class="space-y-6">
            @csrf

            <!-- Pilih Jadwal (Card Based) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-calendar-alt mr-1"></i> Pilih Jadwal Tayang
                </label>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($jadwals as $jadwal)
                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition">
                            <input type="radio" name="jadwal_id" value="{{ $jadwal->jadwal_id }}" data-harga="{{ $jadwal->harga_reguler }}" class="mt-1" required>
                            <div class="ml-4 flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $jadwal->film->judul }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-building text-gray-400 mr-1"></i> {{ $jadwal->studio->nama_studio }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-calendar text-gray-400 mr-1"></i> {{ $jadwal->tanggal_tayang->format('d M Y') }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-clock text-gray-400 mr-1"></i> {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xl font-bold text-blue-600">Rp {{ number_format($jadwal->harga_reguler, 0, ',', '.') }}</p>
                                        <span class="inline-block mt-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">{{ $jadwal->status_jadwal }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @if($jadwals->isEmpty())
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-700 text-sm">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Tidak ada jadwal yang tersedia hari ini.
                    </div>
                @endif
            </div>

            <!-- Pilih Kursi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-chair mr-1"></i> Pilih Kursi
                </label>
                <div id="kursiContainer" class="grid grid-cols-8 gap-2 p-4 bg-gray-50 rounded-lg">
                    <p class="col-span-8 text-gray-600 text-sm text-center py-8">Pilih jadwal terlebih dahulu</p>
                </div>
            </div>

            <!-- Summary -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-700">Jumlah Kursi:</span>
                    <span id="jumlahKursi" class="font-bold text-gray-900">0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Harga/Kursi:</span>
                    <span id="hargaPerKursi" class="font-bold text-gray-900">Rp 0</span>
                </div>
                <div class="border-t border-blue-200 pt-3 flex justify-between">
                    <span class="font-bold text-gray-900">Total Harga:</span>
                    <span id="totalHarga" class="text-2xl font-bold text-blue-600">Rp 0</span>
                </div>
            </div>

            <!-- Button -->
            <div class="flex gap-3">
                <a href="{{ route('kasir.dashboard') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 rounded-lg transition text-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition">
                    <i class="fas fa-arrow-right mr-2"></i> Lanjut ke Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const jadwalInputs = document.querySelectorAll('input[name="jadwal_id"]');
    const kursiContainer = document.getElementById('kursiContainer');
    const jumlahKursiSpan = document.getElementById('jumlahKursi');
    const hargaPerKursiSpan = document.getElementById('hargaPerKursi');
    const totalHargaSpan = document.getElementById('totalHarga');
    const pemesananForm = document.getElementById('pemesananForm');
    
    let selectedKursis = [];

    // Event ketika jadwal dipilih
    jadwalInputs.forEach(input => {
        input.addEventListener('change', async function() {
            const jadwalId = this.value;
            const harga = this.dataset.harga;
            
            hargaPerKursiSpan.textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');

            try {
                // Ambil kursi dari server
                const response = await fetch(`{{ route('kasir.kursi.available', '') }}/${jadwalId}`);
                const data = await response.json();
                
                if (!data.success) {
                    kursiContainer.innerHTML = '<p class="col-span-8 text-red-600 text-sm text-center py-8">Error: ' + data.message + '</p>';
                    return;
                }

                // Clear container
                kursiContainer.innerHTML = '';
                selectedKursis = [];
                updateSummary(harga);

                // Render setiap kursi
                data.kursis.forEach(kursi => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'p-2 border-2 border-gray-300 rounded-lg font-medium text-sm transition';
                    btn.textContent = kursi.kode_kursi;
                    
                    if (kursi.tersedia) {
                        btn.classList.add('hover:border-blue-500', 'hover:bg-blue-50', 'cursor-pointer', 'bg-white', 'text-gray-900');
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            toggleKursi(btn, kursi.kursi_id, harga);
                        });
                    } else {
                        btn.classList.add('bg-gray-200', 'text-gray-500', 'cursor-not-allowed', 'border-gray-300');
                        btn.disabled = true;
                    }

                    kursiContainer.appendChild(btn);
                });
            } catch (error) {
                console.error('Error:', error);
                kursiContainer.innerHTML = '<p class="col-span-8 text-red-600 text-sm text-center py-8">Error mengambil data kursi</p>';
            }
        });
    });

    // Toggle pemilihan kursi
    function toggleKursi(btn, kursiId, harga) {
        const index = selectedKursis.findIndex(k => k.id === kursiId);
        
        if (index > -1) {
            // Deselect
            selectedKursis.splice(index, 1);
            btn.classList.remove('border-blue-500', 'bg-blue-100');
            btn.classList.add('border-gray-300', 'bg-white');
        } else {
            // Select
            selectedKursis.push({ id: kursiId });
            btn.classList.remove('border-gray-300', 'bg-white');
            btn.classList.add('border-blue-500', 'bg-blue-100');
        }

        updateSummary(harga);
    }

    // Update summary
    function updateSummary(harga) {
        jumlahKursiSpan.textContent = selectedKursis.length;
        totalHargaSpan.textContent = 'Rp ' + (selectedKursis.length * parseInt(harga)).toLocaleString('id-ID');
    }

    // Handle form submit
    pemesananForm.addEventListener('submit', function(e) {
        if (selectedKursis.length === 0) {
            e.preventDefault();
            alert('Pilih minimal 1 kursi!');
            return;
        }

        // Buat hidden input untuk kursi_ids
        const kursiIdsInput = document.createElement('input');
        kursiIdsInput.type = 'hidden';
        kursiIdsInput.name = 'kursi_ids';
        kursiIdsInput.value = JSON.stringify(selectedKursis.map(k => k.id));
        
        this.appendChild(kursiIdsInput);
    });
</script>
@endpush