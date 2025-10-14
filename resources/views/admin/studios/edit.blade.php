@extends('layouts.admin')

@section('title', 'Edit Studio')
@section('page-title', 'Edit Studio')

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
        <form action="{{ route('admin.studios.update', $studio->studio_id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Studio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Studio <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_studio" value="{{ old('nama_studio', $studio->nama_studio) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_studio') border-red-500 @enderror"
                    placeholder="Contoh: Studio 1, Studio VIP, Studio IMAX">
                @error('nama_studio')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kapasitas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kapasitas Kursi <span class="text-red-500">*</span>
                </label>
                <input type="number" name="kapasitas" id="kapasitasInput" 
                    value="{{ old('kapasitas', $studio->kapasitas) }}" 
                    min="10" max="500" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kapasitas') border-red-500 @enderror">
                @error('kapasitas')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    Kapasitas saat ini: <strong>{{ $studio->kapasitas }}</strong> kursi
                </p>
            </div>

            <!-- Info Current -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Info Studio Saat Ini</h4>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <p class="text-gray-500">Total Kursi Terdaftar</p>
                        <p class="font-semibold">{{ $studio->kursis->count() }} kursi</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Total Jadwal</p>
                        <p class="font-semibold">{{ $studio->jadwals->count() }} jadwal</p>
                    </div>
                </div>
            </div>

            <!-- Preview Changes -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4" id="previewSection" style="display: none;">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-blue-900">Perubahan Kapasitas</h4>
                        <div class="text-sm text-blue-700 mt-2">
                            <p id="previewMessage"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning if has active jadwals -->
            @if($studio->jadwals()->where('status_jadwal', 'Active')->count() > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-yellow-900">Perhatian!</h4>
                        <p class="text-sm text-yellow-700 mt-1">
                            Studio ini memiliki <strong>{{ $studio->jadwals()->where('status_jadwal', 'Active')->count() }} jadwal aktif</strong>.
                            Hati-hati saat mengubah kapasitas, pastikan tidak mengganggu pemesanan yang sudah ada!
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.studios.index') }}" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    Update Studio
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const kapasitasInput = document.getElementById('kapasitasInput');
    const previewSection = document.getElementById('previewSection');
    const previewMessage = document.getElementById('previewMessage');
    const originalKapasitas = {{ $studio->kapasitas }};

    kapasitasInput.addEventListener('input', function() {
        const newKapasitas = parseInt(this.value) || 0;
        const diff = newKapasitas - originalKapasitas;

        if (diff !== 0) {
            previewSection.style.display = 'block';
            
            if (diff > 0) {
                previewMessage.innerHTML = `
                    Kapasitas akan <strong>ditambah ${diff} kursi</strong>.<br>
                    Sistem akan otomatis menambahkan ${diff} kursi baru.
                `;
            } else {
                previewMessage.innerHTML = `
                    Kapasitas akan <strong>dikurangi ${Math.abs(diff)} kursi</strong>.<br>
                    Sistem akan otomatis menghapus ${Math.abs(diff)} kursi terakhir.
                `;
            }
        } else {
            previewSection.style.display = 'none';
        }
    });
</script>
@endpush