@extends('layouts.admin')

@section('title', 'Kelola Owner')
@section('page-title', 'Kelola Owner')

@section('content')
<div class="space-y-6">
    
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Owner</h2>
            <p class="text-gray-600 text-sm mt-1">Kelola akun owner bioskop</p>
        </div>
        <a href="{{ route('admin.owners.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>
            Tambah Owner Baru
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.owners.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" 
                    placeholder="Cari username, email, atau nama..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            <a href="{{ route('admin.owners.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </form>
    </div>

    <!-- Owner List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        
        @if($owners->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Update</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($owners as $owner)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold">
                                            {{ substr($owner->username, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $owner->username }}</div>
                                        <div class="text-sm text-gray-500">{{ $owner->nama_lengkap }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $owner->email }}</div>
                                <div class="text-sm text-gray-500">{{ $owner->no_telepon }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $owner->created_at->format('d M Y') }}</span>
                                <div class="text-xs text-gray-500">{{ $owner->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $owner->updated_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.owners.show', $owner->user_id) }}" 
                                        class="text-blue-600 hover:text-blue-900" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.owners.edit', $owner->user_id) }}" 
                                        class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.owners.destroy', $owner->user_id) }}" 
                                        method="POST" class="inline" 
                                        onsubmit="return confirm('Yakin ingin menghapus owner ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden p-4 space-y-4">
                @foreach($owners as $owner)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($owner->username, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-semibold text-gray-900">{{ $owner->username }}</h3>
                            <p class="text-sm text-gray-600">{{ $owner->nama_lengkap }}</p>
                            <div class="mt-2 space-y-1 text-sm text-gray-600">
                                <p><i class="fas fa-envelope w-4 text-gray-400"></i> {{ $owner->email }}</p>
                                <p><i class="fas fa-phone w-4 text-gray-400"></i> {{ $owner->no_telepon }}</p>
                            </div>
                            <div class="mt-3 text-xs text-gray-500">
                                <p>Bergabung: {{ $owner->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center space-x-3 mt-3">
                                <a href="{{ route('admin.owners.show', $owner->user_id) }}" 
                                    class="text-blue-600 hover:text-blue-900 text-sm">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                <a href="{{ route('admin.owners.edit', $owner->user_id) }}" 
                                    class="text-yellow-600 hover:text-yellow-900 text-sm">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <form action="{{ route('admin.owners.destroy', $owner->user_id) }}" 
                                    method="POST" class="inline" 
                                    onsubmit="return confirm('Yakin ingin menghapus owner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $owners->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-crown text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada owner</h3>
                <p class="text-gray-600 mb-4">Mulai tambahkan akun owner baru</p>
                <a href="{{ route('admin.owners.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Owner Pertama
                </a>
            </div>
        @endif
    </div>

</div>
@endsection