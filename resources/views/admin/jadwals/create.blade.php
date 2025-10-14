@extends('layouts.admin')

@section('title', 'Tambah Jadwal')
@section('page-title', 'Tambah Jadwal Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.jadwals.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Jadwal
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
        <form action="{{ route('admin.jadwals.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Film Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Film <span class="text-red-500">*</span>
                </label>
                <select name="film_id" id="filmSelect" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('film_id') border-red-500 @enderror">
                    <option value="">-- Pilih Film --</option>
                    @foreach($films as $film)
                        <option value="{{ $film->film_id }}" 
                            data-durasi="{{ $film->durasi_menit }}"
                            {{ old('film_id') == $film->film_id ? 'selected' : '' }}>
                            {{ $film->judul }} ({{ $film->durasi_menit }} menit) - {{ $film->status_tayang }}
                        </option>
                    @endforeach
                </select>
                @error('film_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Studio Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Studio <span class="text-red-500">*</span>
                </label>
                <select name="studio_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('studio_id') border-red-500 @enderror">
                    <option value="">-- Pilih Studio --</option>
                    @foreach($studios as $studio)
                        <option value="{{ $studio->studio_id }}" {{ old('studio_id') == $studio->studio_id ? 'selected' : '' }}>
                            {{ $studio->nama_studio }} (Kapasitas: {{ $studio->kapasitas }} kursi)
                        </option>
                    @endforeach
                </select>
                @error('studio_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date & Time Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Tanggal Tayang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Tayang <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_tayang" value="{{ old('tanggal_tayang') }}" 
                        min="{{ date('Y-m-d') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_tayang') border-red-500 @enderror">
                    @error('tanggal_tayang')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Mulai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_mulai" id="jamMulai" value="{{ old('jam_mulai') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jam_mulai') border-red-500 @enderror">
                    @error('jam_mulai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Jam Selesai (Auto Calculate) -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-900">Info Waktu Tayang</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Jam selesai akan dihitung otomatis: <strong id="jamSelesaiInfo">-</strong>
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            (Durasi film + 15 menit buffer untuk persiapan & cleaning)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Harga Tiket <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-2 text-gray-500">Rp</span>
                    <input type="number" name="harga_reguler" value="{{ old('harga_reguler', 50000) }}" 
                        min="0" step="1000" required
                        class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('harga_reguler') border-red-500 @enderror"
                        placeholder="50000">
                </div>
                @error('harga_reguler')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Harga per kursi reguler</p>
            </div>

            <!-- Status Jadwal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status Jadwal <span class="text-red-500">*</span>
                </label>
                <select name="status_jadwal" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status_jadwal') border-red-500 @enderror">
                    <option value="Active" {{ old('status_jadwal', 'Active') == 'Active' ? 'selected' : '' }}>Active (Bisa Dipesan)</option>
                    <option value="Canceled" {{ old('status_jadwal') == 'Canceled' ? 'selected' : '' }}>Canceled (Dibatalkan)</option>
                    <option value="Full" {{ old('status_jadwal') == 'Full' ? 'selected' : '' }}>Full (Penuh)</option>
                </select>
                @error('status_jadwal')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.jadwals.index') }}" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Jadwal
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto calculate jam_selesai based on film duration
    const filmSelect = document.getElementById('filmSelect');
    const jamMulaiInput = document.getElementById('jamMulai');
    const jamSelesaiInfo = document.getElementById('jamSelesaiInfo');

    function calculateJamSelesai() {
        const selectedFilm = filmSelect.options[filmSelect.selectedIndex];
        const durasi = selectedFilm.getAttribute('data-durasi');
        const jamMulai = jamMulaiInput.value;

        if (durasi && jamMulai) {
            const [hours, minutes] = jamMulai.split(':');
            const startTime = new Date();
            startTime.setHours(parseInt(hours), parseInt(minutes), 0);

            // Add film duration + 15 minutes buffer
            const totalMinutes = parseInt(durasi) + 15;
            startTime.setMinutes(startTime.getMinutes() + totalMinutes);

            const endHours = String(startTime.getHours()).padStart(2, '0');
            const endMinutes = String(startTime.getMinutes()).padStart(2, '0');

            jamSelesaiInfo.textContent = `${endHours}:${endMinutes} (${durasi} menit film + 15 menit buffer)`;
        } else {
            jamSelesaiInfo.textContent = '-';
        }
    }

    filmSelect.addEventListener('change', calculateJamSelesai);
    jamMulaiInput.addEventListener('change', calculateJamSelesai);

    // Calculate on page load if values exist
    if (filmSelect.value && jamMulaiInput.value) {
        calculateJamSelesai();
    }
</script>
@endpush