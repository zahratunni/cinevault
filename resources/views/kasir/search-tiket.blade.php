@extends('layouts.kasir')

@section('title', 'Cetak Tiket - Kasir')
@section('page-title', 'Cetak Tiket')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">
            <i class="fas fa-search mr-2 text-blue-600"></i> Cari Pemesanan Berdasarkan Kode Booking
        </h2>

        <form method="POST" action="{{ route('kasir.tiket.cari') }}" class="space-y-6">
            @csrf

            <!-- Input Kode Booking -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-ticket-alt mr-1"></i> Kode Booking
                </label>
                <input 
                    type="text" 
                    name="kode_booking" 
                    required 
                    placeholder="Masukkan kode booking (contoh: TRX-20251020123456-A1B2)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg font-mono"
                    autofocus
                >
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i> Customer dapat kode booking setelah pemesanan online berhasil
                </p>
            </div>

            <!-- Button -->
            <div class="flex gap-3">
                <a href="{{ route('kasir.dashboard') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 rounded-lg transition text-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i> Cari Pemesanan
                </button>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="font-medium text-blue-900 mb-2">
            <i class="fas fa-lightbulb mr-2"></i> Informasi
        </h3>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Halaman ini untuk mencetak tiket customer yang pesan secara online</li>
            <li>• Customer memberikan kode booking kepada kasir</li>
            <li>• Kasir input kode booking → sistem load detail pemesanan → cetak tiket</li>
            <li>• Tiket hanya bisa dicetak jika pemesanan sudah lunas</li>
        </ul>
    </div>
</div>
@endsection