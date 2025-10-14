@extends('layouts.admin')

@section('title', 'Tambah Studio')
@section('page-title', 'Tambah Studio Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.studios.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Studio
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
        <form action="{{ route('admin.studios.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Studio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Studio <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_studio" value="{{ old('nama_studio') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_studio') border-red-500 @enderror"
                    placeholder="Contoh: Studio 1, Studio VIP, Studio IMAX">
                @error('nama_studio')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Nama studio harus unik dan maksimal 50 karakter</p>
            </div>

            <!-- Kapasitas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kapasitas Kursi <span class="text-red-500">*</span>
                </label>
                <input type="number" name="kapasitas" id="kapasitasInput" value="{{ old('kapasitas', 50) }}" 
                    min="10" max="500" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kapasitas') border-red-500 @enderror"
                    placeholder="50">
                @error('kapasitas')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Minimal 10 kursi, maksimal 500 kursi</p>
            </div>

            <!-- Info Preview -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-blue-900">Info Kursi yang Akan Dibuat</h4>
                        <div class="text-sm text-blue-700 mt-2 space-y-1">
                            <p>Jumlah kursi: <strong id="previewKapasitas">50</strong> kursi</p>
                            <p>Jumlah baris: <strong id="previewBaris">5</strong> baris (A, B, C, ...)</p>
                            <p>Kursi per baris: <strong>10</strong> kursi</p>
                            <p class="mt-2 text-xs">Contoh kode kursi: A1, A2, A3, ..., B1, B2, B3, ...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-yellow-900">Perhatian!</h4>
                        <p class="text-sm text-yellow-700 mt-1">
                            Sistem akan otomatis membuat <strong id="warningKapasitas">50</strong> kursi untuk studio ini.
                            Anda bisa mengubah kapasitas nanti, tapi hati-hati jika sudah ada jadwal aktif.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.studios.index') }}" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Studio
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const kapasitasInput = document.getElementById('kapasitasInput');
    const previewKapasitas = document.getElementById('previewKapasitas');
    const previewBaris = document.getElementById('previewBaris');
    const warningKapasitas = document.getElementById('warningKapasitas');

    function updatePreview() {
        const kapasitas = parseInt(kapasitasInput.value) || 0;
        const kursiPerBaris = 10;
        const jumlahBaris = Math.ceil(kapasitas / kursiPerBaris);

        previewKapasitas.textContent = kapasitas;
        previewBaris.textContent = jumlahBaris;
        warningKapasitas.textContent = kapasitas;
    }

    kapasitasInput.addEventListener('input', updatePreview);

    // Initial preview
    updatePreview();
</script>
@endpush