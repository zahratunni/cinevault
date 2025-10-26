@extends('layouts.admin')

@section('title', 'Detail Owner')
@section('page-title', 'Detail Owner')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.owners.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Owner
        </a>
    </div>

    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-8 text-white">
        <div class="flex items-center space-x-6">
            <div class="flex-shrink-0">
                <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center">
                    <span class="text-4xl font-bold text-purple-600">{{ substr($owner->username, 0, 1) }}</span>
                </div>
            </div>
            <div>
                <h2 class="text-3xl font-bold">{{ $owner->nama_lengkap ?: $owner->username }}</h2>
                <p class="text-purple-100 mt-1">{{ '@' . $owner->username }}</p>
                <div class="flex items-center mt-3 space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white bg-opacity-20">
                        <i class="fas fa-crown mr-2"></i> {{ $owner->role }}
                    </span>
                    <span class="text-sm text-purple-100">
                        <i class="fas fa-calendar mr-1"></i> Member since {{ $owner->created_at->format('M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.owners.edit', $owner->user_id) }}" 
            class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
            <i class="fas fa-edit mr-2"></i>
            Edit Owner
        </a>
        <form action="{{ route('admin.owners.destroy', $owner->user_id) }}" 
            method="POST" class="inline" 
            onsubmit="return confirm('Yakin ingin menghapus owner ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-trash mr-2"></i>
                Hapus Owner
            </button>
        </form>
    </div>

    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-user mr-2 text-purple-600"></i>
                Informasi Profile
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-1"></i> Username
                    </label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 font-medium">
                        {{ $owner->username }}
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1"></i> Email
                    </label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 font-medium">
                        {{ $owner->email }}
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-id-card mr-1"></i> Nama Lengkap
                    </label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800">
                        {{ $owner->nama_lengkap ?? '-' }}
                    </div>
                </div>

                <!-- No Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1"></i> No. Telepon
                    </label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800">
                        {{ $owner->no_telepon ?? '-' }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Account Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Account Info -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-info-circle mr-2 text-purple-600"></i>
                    Detail Akun
                </h3>
            </div>
            <div class="p-6 space-y-4">
                
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Account ID</span>
                    <span class="text-sm font-semibold text-gray-800">#{{ $owner->user_id }}</span>
                </div>

                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Role</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <i class="fas fa-crown mr-1"></i> {{ $owner->role }}
                    </span>
                </div>

                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Status Akun</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Active
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Level Akses</span>
                    <span class="text-sm font-semibold text-gray-800">Full Access (View Only)</span>
                </div>

            </div>
        </div>

        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-clock mr-2 text-purple-600"></i>
                    Timeline Aktivitas
                </h3>
            </div>
            <div class="p-6 space-y-4">
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-user-plus text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800">Akun Dibuat</p>
                        <p class="text-xs text-gray-500">{{ $owner->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $owner->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-sync text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800">Terakhir Diupdate</p>
                        <p class="text-xs text-gray-500">{{ $owner->updated_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $owner->updated_at->diffForHumans() }}</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Info Notice -->
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-purple-500 mt-0.5 mr-3"></i>
            <div>
                <h4 class="text-sm font-medium text-purple-900">Tentang Role Owner</h4>
                <p class="text-sm text-purple-700 mt-1">
                    Owner memiliki akses untuk melihat dashboard bisnis, laporan pemasukan, dan statistik performa bioskop. 
                    Owner <strong>tidak dapat</strong> melakukan perubahan data operasional - semua pengelolaan dilakukan oleh Admin.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection