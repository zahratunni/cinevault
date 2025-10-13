<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\DetailPemesanan;
use App\Models\Pemesanan;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Menampilkan halaman pemilihan kursi untuk jadwal tertentu.
     */
    public function create($jadwal_id)
    {
        $jadwal = Jadwal::with(['film', 'studio'])->findOrFail($jadwal_id);
        
        // Ambil semua kursi di studio ini
        $kursis = Kursi::where('studio_id', $jadwal->studio_id)
            ->orderBy('baris')
            ->orderBy('nomor_kursi')
            ->get();
        
        // Ambil kursi yang sudah dibooking untuk jadwal ini
        $bookedKursiIds = DetailPemesanan::whereHas('pemesanan', function($query) use ($jadwal_id) {
            $query->where('jadwal_id', $jadwal_id)
                  ->whereIn('status_pemesanan', ['Lunas', 'Menunggu Bayar']); 
        })->pluck('kursi_id')->toArray();
        
        // Group kursi by baris untuk tampilan di view
        $kursisByBaris = $kursis->groupBy('baris');
        
        return view('films.seat-selection', compact('jadwal', 'kursisByBaris', 'bookedKursiIds'));
    }

    /**
     * Memproses permintaan booking (membuat record Pemesanan).
     */
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,jadwal_id',
            'kursi_ids' => 'required|array|min:1',
            'kursi_ids.*' => 'exists:kursis,kursi_id',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $kursiIds = $request->kursi_ids;
        
        // === VALIDASI 1: Jadwal maksimal H+1 (hari ini atau besok) ===
        $maxDate = now()->addDay()->endOfDay();
        $jadwalDateTime = \Carbon\Carbon::parse($jadwal->tanggal_tayang . ' ' . $jadwal->jam_mulai);
        
        if ($jadwalDateTime->gt($maxDate)) {
            return back()->with('error', 'Hanya bisa booking untuk hari ini atau besok.');
        }
        
        // === VALIDASI 2: Jadwal belum lewat ===
        if ($jadwalDateTime->isPast()) {
            return back()->with('error', 'Jadwal ini sudah lewat. Silakan pilih jadwal lain.');
        }
        
        // === VALIDASI 3: Status jadwal masih Active ===
        if ($jadwal->status_jadwal !== 'Active') {
            return back()->with('error', 'Jadwal ini sudah tidak tersedia.');
        }
        
        // === VALIDASI 4: Cek kursi masih available ===
        $bookedKursi = DetailPemesanan::whereHas('pemesanan', function($query) use ($request) {
            $query->where('jadwal_id', $request->jadwal_id)
                  ->whereIn('status_pemesanan', ['Lunas', 'Menunggu Bayar']);
        })->whereIn('kursi_id', $kursiIds)->exists();
        
        if ($bookedKursi) {
            return back()->with('error', 'Maaf, ada kursi yang sudah dibooking orang lain. Silakan pilih kursi lain.');
        }
        
        // Hitung total pembayaran
        $jumlahKursi = count($kursiIds);
        $hargaPerKursi = $jadwal->harga_reguler;
        $totalBayar = $jumlahKursi * $hargaPerKursi;
        
        // Buat pemesanan
        $pemesanan = Pemesanan::create([
            'user_id' => auth()->id(),
            'jadwal_id' => $request->jadwal_id,
            'kode_transaksi' => 'TRX-' . strtoupper(Str::random(10)),
            'jenis_pemesanan' => 'Online',
            'status_pemesanan' => 'Menunggu Bayar',
            'harga_dasar_total' => $totalBayar,
            'total_bayar' => $totalBayar,
            'tanggal_pemesanan' => now(),
        ]);
        
        // Buat detail pemesanan untuk setiap kursi
        foreach ($kursiIds as $kursiId) {
            DetailPemesanan::create([
                'pemesanan_id' => $pemesanan->pemesanan_id,
                'kursi_id' => $kursiId,
                'harga_per_kursi' => $hargaPerKursi,
            ]);
        }
        
        // Redirect ke halaman success
        return redirect()->route('booking.success', $pemesanan->pemesanan_id)
                         ->with('success', 'Booking berhasil! Silakan lanjutkan pembayaran.');
    }

    /**
     * Menampilkan halaman sukses booking dengan detail pemesanan.
     */
    public function success($pemesanan_id)
    {
        // Ambil data pemesanan dengan relasi yang diperlukan
        $pemesanan = Pemesanan::with([
            'jadwal.film',
            'jadwal.studio',
            'detailPemesanans.kursi',
            'user'
        ])->findOrFail($pemesanan_id);
        
        // Pastikan pemesanan ini milik user yang login (security check)
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini.');
        }
        
        // SESUAIKAN DENGAN LOKASI FILE VIEW ANDA
        return view('films.booking-success', compact('pemesanan')); 
    }
}