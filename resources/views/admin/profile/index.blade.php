@extends('layouts.admin')

@section('title', 'Profile Saya')
@section('page-title', 'Profile Saya')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Profile Header Card -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg text-white p-8">
        <div class="flex items-center space-x-6">
            <div class="flex-shrink-0">
                <div class="h-24 w-24 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-white text-4xl font-bold border-4 border-white border-opacity-30">
                    {{ substr($admin->username, 0, 1) }}
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold">{{ $admin->nama_lengkap ?? $admin->username }}</h1>
                <p class="text-blue-100 mt-1">{{ $admin->role }} - CineVault</p>
                <p class="text-blue-100 text-sm mt-2">
                    <i class="fas fa-clock mr-1"></i>
                    Bergabung sejak {{ $admin->created_at->format('d F Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Profile</h2>
        
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Username (Readonly) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Username
                </label>
                <div class="bg-gray-100 px-4 py-2 rounded-lg border border-gray-300">
                    <p class="text-gray-900">{{ $admin->username }}</p>
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
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                    placeholder="admin@cinevault.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $admin->nama_lengkap) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_lengkap') border-red-500 @enderror"
                    placeholder="Nama lengkap Anda">
                @error('nama_lengkap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No Telepon -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    No. Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon', $admin->no_telepon) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_telepon') border-red-500 @enderror"
                    placeholder="08123456789">
                @error('no_telepon')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role (Readonly) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Role
                </label>
                <div class="bg-gray-100 px-4 py-2 rounded-lg border border-gray-300">
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $admin->role }}
                    </span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

    <!-- Change Password Form -->
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Ubah Password</h2>
        
        <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Password Lama <span class="text-red-500">*</span>
                </label>
                <input type="password" name="current_password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_password') border-red-500 @enderror"
                    placeholder="Masukkan password lama">
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ulangi password baru">
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-yellow-900">Perhatian!</h4>
                        <p class="text-sm text-yellow-700 mt-1">
                            Setelah password diubah, Anda akan tetap login. Pastikan Anda mengingat password baru untuk login berikutnya.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <button type="submit" 
                    class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                    <i class="fas fa-key mr-2"></i>
                    Ubah Password
                </button>
            </div>

        </form>
    </div>

    <!-- Account Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akun</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">ID User</p>
                <p class="text-gray-900 font-medium">{{ $admin->user_id }}</p>
            </div>
            <div>
                <p class="text-gray-500">Terdaftar Pada</p>
                <p class="text-gray-900 font-medium">{{ $admin->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Terakhir Diupdate</p>
                <p class="text-gray-900 font-medium">{{ $admin->updated_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Status Akun</p>
                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i>Aktif
                </span>
            </div>
        </div>
    </div>

</div>
@endsection