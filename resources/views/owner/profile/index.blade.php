@extends('layouts.owner')

@section('title', 'Profile Owner')
@section('page-title', 'My Profile')

@section('content')
<div class="space-y-6">

    <!-- Profile Header Card -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-8 text-white">
        <div class="flex items-center space-x-6">
            <div class="flex-shrink-0">
                <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center">
                    <span class="text-4xl font-bold text-purple-600">{{ substr($user->username, 0, 1) }}</span>
                </div>
            </div>
            <div>
                <h2 class="text-3xl font-bold">{{ $user->nama_lengkap ?: $user->username }}</h2>
                <p class="text-purple-100 mt-1">{{ '@' . $user->username }}</p>
                <div class="flex items-center mt-3 space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white bg-opacity-20">
                        <i class="fas fa-crown mr-2"></i> {{ $user->role }}
                    </span>
                    <span class="text-sm text-purple-100">
                        <i class="fas fa-calendar mr-1"></i> Member since {{ $user->created_at->format('M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-blue-800 mb-1">Informasi Penting</h3>
                <p class="text-sm text-blue-700">
                    Akun Owner dikelola sepenuhnya oleh <strong>Administrator</strong>. Untuk perubahan data profile atau password, silakan hubungi Administrator sistem.
                </p>
            </div>
        </div>
    </div>

    <!-- Profile Information (Read Only) -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-user mr-2 text-purple-600"></i>
                Profile Information
            </h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                <i class="fas fa-lock mr-1"></i> Read Only
            </span>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-1"></i> Username
                    </label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 font-medium">
                        {{ $user->username }}
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1"></i> Email
                    </label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 font-medium">
                        {{ $user->email }}
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-id-card mr-1"></i> Nama Lengkap
                    </label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800">
                        {{ $user->nama_lengkap ?? '-' }}
                    </div>
                </div>

                <!-- No Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1"></i> No. Telepon
                    </label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800">
                        {{ $user->no_telepon ?? '-' }}
                    </div>
                </div>

            </div>

            <!-- Notice -->
            <div class="mt-6 bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-shield-alt text-purple-500 mt-0.5 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-purple-900 mb-1">Keamanan Akun</p>
                        <p class="text-sm text-purple-700">
                            Semua perubahan data profile dan password dilakukan oleh Administrator untuk menjaga keamanan dan integritas sistem.
                        </p>
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
                    Account Details
                </h3>
            </div>
            <div class="p-6 space-y-4">
                
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Account ID</span>
                    <span class="text-sm font-semibold text-gray-800">#{{ $user->user_id }}</span>
                </div>

                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Role</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <i class="fas fa-crown mr-1"></i> {{ $user->role }}
                    </span>
                </div>

                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Account Status</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Active
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Access Level</span>
                    <span class="text-sm font-semibold text-gray-800">Full Access</span>
                </div>

            </div>
        </div>

        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-clock mr-2 text-purple-600"></i>
                    Activity Timeline
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
                        <p class="text-sm font-medium text-gray-800">Account Created</p>
                        <p class="text-xs text-gray-500">{{ $user->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-sync text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800">Last Updated</p>
                        <p class="text-xs text-gray-500">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Contact Admin Card -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-purple-600 flex items-center justify-center">
                        <i class="fas fa-user-shield text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Perlu Mengubah Data?</h3>
                    <p class="text-sm text-gray-600">Hubungi Administrator untuk perubahan profile atau password</p>
                </div>
            </div>
            <div>
                <a href="#" onclick="alert('Silakan hubungi Administrator sistem untuk perubahan data.')" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    <i class="fas fa-envelope mr-2"></i>
                    Contact Admin
                </a>
            </div>
        </div>
    </div>

</div>
@endsection