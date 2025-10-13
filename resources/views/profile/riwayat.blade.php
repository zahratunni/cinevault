@extends('layouts.app')

@section('content')
<div class="bg-[#1a1a1a] min-h-screen text-white py-10">
    <div class="max-w-5xl mx-auto bg-[#2a2a2a] rounded-2xl p-8 shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-[#FEA923]">Riwayat Transaksi</h2>

        @if ($riwayat->isEmpty())
            <p class="text-center text-gray-400">Belum ada riwayat pemesanan.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#FEA923] text-black">
                            <th class="p-3 rounded-l-lg">Kode Transaksi</th>
                            <th class="p-3">Jenis</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Total Bayar</th>
                            <th class="p-3 rounded-r-lg">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat as $item)
                            <tr class="border-b border-gray-700 hover:bg-[#3a3a3a]">
                                <td class="p-3">{{ $item->kode_transaksi }}</td>
                                <td class="p-3">{{ $item->jenis_pemesanan }}</td>
                                <td class="p-3">{{ $item->status_pemesanan }}</td>
                                <td class="p-3">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal_pemesanan)->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="text-center mt-8">
            <a href="{{ route('profile.index') }}" 
               class="bg-gray-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-500">
                Kembali ke Profil
            </a>
        </div>
    </div>
</div>
@endsection
