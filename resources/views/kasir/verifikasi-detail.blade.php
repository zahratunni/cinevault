@extends('layouts.kasir')

@section('title', 'Detail Verifikasi Pembayaran')
@section('page-title', 'Detail Pembayaran #' . $pembayaran->pembayaran_id)

@section('content')
<div class="space-y-6">
    
    <!-- Back Button -->
    <a href="{{ route('kasir.verifikasi-online.index') }}" 
       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Info & Bukti -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Informasi Pembayaran -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-bold">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Pembayaran
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">ID Pembayaran</p>
                            <p class="text-lg font-bold text-gray-900">#{{ $pembayaran->pembayaran_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Kode Transaksi</p>
                            <p class="text-lg font-bold text-blue-600">{{ $pembayaran->pemesanan->kode_transaksi }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Customer</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $pembayaran->pemesanan->user?->nama_lengkap ?? $pembayaran->pemesanan->user?->username ?? 'Guest' }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $pembayaran->pemesanan->user?->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Pembayaran</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $pembayaran->tanggal_pembayaran->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Metode Pembayaran</p>
                            <span class="inline-block mt-1 px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $pembayaran->metode_online ?? $pembayaran->metode_bayar }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status Pembayaran</p>
                            @if($pembayaran->status_pembayaran == 'Pending')
                                <span class="inline-block mt-1 px-4 py-2 text-base font-bold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @elseif($pembayaran->status_pembayaran == 'Lunas')
                                <span class="inline-block mt-1 px-4 py-2 text-base font-bold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Lunas
                                </span>
                            @else
                                <span class="inline-block mt-1 px-4 py-2 text-base font-bold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                </span>
                            @endif
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Nominal Dibayar</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">
                                Rp {{ number_format($pembayaran->nominal_dibayar, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Pemesanan -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-purple-600 text-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-bold">
                        <i class="fas fa-ticket-alt mr-2"></i>
                        Detail Pemesanan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex gap-4 items-start">
                        <img src="{{ asset($pembayaran->pemesanan->jadwal->film->poster_url ?? '/placeholder.jpg') }}" 
                             alt="Poster" 
                             class="w-24 h-36 object-cover rounded-lg shadow">
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900 mb-2">
                                {{ $pembayaran->pemesanan->jadwal->film->judul }}
                            </h4>
                            <div class="space-y-2 text-sm">
                                <p><i class="fas fa-door-open text-purple-600 w-5"></i> <strong>Studio:</strong> {{ $pembayaran->pemesanan->jadwal->studio->nama_studio }}</p>
                                <p><i class="fas fa-calendar text-purple-600 w-5"></i> <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pembayaran->pemesanan->jadwal->tanggal_tayang)->format('d F Y') }}</p>
                                <p><i class="fas fa-clock text-purple-600 w-5"></i> <strong>Jam:</strong> {{ \Carbon\Carbon::parse($pembayaran->pemesanan->jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($pembayaran->pemesanan->jadwal->jam_selesai)->format('H:i') }} WIB</p>
                                <p>
                                    <i class="fas fa-couch text-purple-600 w-5"></i> <strong>Kursi:</strong>
                                    @foreach($pembayaran->pemesanan->detailPemesanans as $detail)
                                        <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-bold ml-1">
                                            {{ $detail->kursi->kode_kursi }}
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-cyan-600 text-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-bold">
                        <i class="fas fa-image mr-2"></i>
                        Bukti Pembayaran
                    </h3>
                </div>
                <div class="p-6">
                    @if($pembayaran->bukti_pembayaran)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                 alt="Bukti Pembayaran" 
                                 class="max-w-full h-auto rounded-lg shadow-md cursor-pointer hover:shadow-xl transition mx-auto"
                                 style="max-height: 600px;"
                                 onclick="openImageModal(this.src)">
                            <p class="text-gray-500 text-sm mt-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Klik gambar untuk memperbesar
                            </p>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-file-image text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500">Bukti pembayaran belum diupload oleh customer</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Verifikasi (Jika sudah diverifikasi) -->
            @if($pembayaran->isVerified())
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-700 text-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-bold">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Riwayat Verifikasi
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Diverifikasi Oleh</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $pembayaran->verifiedBy?->nama_lengkap ?? $pembayaran->verifiedBy?->username ?? 'Kasir' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Verifikasi</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $pembayaran->verified_at?->format('d F Y, H:i') ?? '-' }} WIB
                        </p>
                    </div>
                    @if($pembayaran->rejection_reason)
                    <div>
                        <p class="text-sm text-gray-500">Alasan Penolakan</p>
                        <p class="text-base text-red-600 font-semibold bg-red-50 p-3 rounded">
                            {{ $pembayaran->rejection_reason }}
                        </p>
                    </div>
                    @endif
                    @if($pembayaran->admin_notes)
                    <div>
                        <p class="text-sm text-gray-500">Catatan Kasir</p>
                        <p class="text-base text-gray-900 bg-gray-50 p-3 rounded">
                            {{ $pembayaran->admin_notes }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Actions -->
        <div class="space-y-6">
            
            @if($pembayaran->status_pembayaran == 'Pending')
                
                <!-- Form Approve -->
                <div class="bg-white rounded-lg shadow-lg border-2 border-green-500">
                    <div class="bg-green-500 text-white px-6 py-4 rounded-t-lg">
                        <h3 class="text-lg font-bold">
                            <i class="fas fa-check-circle mr-2"></i>
                            Setujui Pembayaran
                        </h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('kasir.verifikasi-online.approve', $pembayaran->pembayaran_id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin menyetujui pembayaran ini?')">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan (Opsional)
                                </label>
                                <textarea name="admin_notes" 
                                          rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                          placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition">
                                <i class="fas fa-check mr-2"></i>
                                SETUJUI PEMBAYARAN
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Form Reject -->
                <div class="bg-white rounded-lg shadow-lg border-2 border-red-500">
                    <div class="bg-red-500 text-white px-6 py-4 rounded-t-lg">
                        <h3 class="text-lg font-bold">
                            <i class="fas fa-times-circle mr-2"></i>
                            Tolak Pembayaran
                        </h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('kasir.verifikasi-online.reject', $pembayaran->pembayaran_id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin menolak pembayaran ini?')">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="rejection_reason" 
                                          rows="3" 
                                          required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('rejection_reason') border-red-500 @enderror"
                                          placeholder="Contoh: Bukti tidak jelas, nominal tidak sesuai..."></textarea>
                                @error('rejection_reason')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan Tambahan (Opsional)
                                </label>
                                <textarea name="admin_notes" 
                                          rows="2" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-lg transition">
                                <i class="fas fa-times mr-2"></i>
                                TOLAK PEMBAYARAN
                            </button>
                        </form>
                    </div>
                </div>

            @else
                
                <!-- Status Sudah Diverifikasi -->
                <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                    @if($pembayaran->status_pembayaran == 'Lunas')
                        <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                        <h3 class="text-xl font-bold text-green-600 mb-2">Pembayaran Sudah Disetujui</h3>
                    @else
                        <i class="fas fa-times-circle text-red-500 text-6xl mb-4"></i>
                        <h3 class="text-xl font-bold text-red-600 mb-2">Pembayaran Ditolak</h3>
                    @endif
                    <div class="text-gray-600 text-sm mt-4 space-y-1">
                        <p><strong>Diverifikasi oleh:</strong></p>
                        <p>{{ $pembayaran->verifiedBy?->nama_lengkap ?? $pembayaran->verifiedBy?->username ?? 'Kasir' }}</p>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $pembayaran->verified_at?->format('d/m/Y H:i') ?? '-' }} WIB
                        </p>
                    </div>
                </div>

            @endif
        </div>
    </div>
</div>

<!-- Modal untuk Zoom Gambar -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-full">
    <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300">
        <i class="fas fa-times"></i>
    </button>
</div>

@push('scripts')
<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}
</script>
@endpush
@endsection