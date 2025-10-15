@extends('layouts.admin')

@section('title', 'Edit Kasir')
@section('page-title', 'Edit Data Kasir')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.kasirs.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Kasir
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
        <form action="{{ route('admin.kasirs.update', $kasir->user_id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Username (Readonly) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Username
                </label>
                <div class="bg-gray-100 px-4 py-2 rounded-lg border border-gray-300">
                    <p class="text-gray-900">{{ $kasir->username }}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle"></i> Username tidak dapat diubah
                </p>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $kasir->email) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                    placeholder="kasir@cinevault.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $kasir->nama_lengkap) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_lengkap') border-red-500 @enderror"
                    placeholder="Nama lengkap kasir">
                @error('nama_lengkap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No Telepon -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    No. Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon', $kasir->no_telepon) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_telepon') border-red-500 @enderror"
                    placeholder="08123456789">
                @error('no_telepon')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Divider -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubah Password (Opsional)</h3>
                <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>
            </div>

            <!-- Password Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="••••••••">
                </div>
            </div>

            <!-- Info Current Stats -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Statistik Kasir</h4>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <p class="text-gray-500">Total Transaksi</p>
                        <p class="font-semibold">{{ $kasir->pembayarans->count() }} transaksi</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Jadwal Dibuat</p>
                        <p class="font-semibold">{{ $kasir->jadwals->count() }} jadwal</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Bergabung Sejak</p>
                        <p class="font-semibold">{{ $kasir->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Terakhir Update</p>
                        <p class="font-semibold">{{ $kasir->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.kasirs.index') }}" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    Update Data
                </button>
            </div>

        </form>
    </div>
</div>
@endsection