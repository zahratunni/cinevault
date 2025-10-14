<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\Kursi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminStudioController extends Controller
{
    /**
     * Display a listing of studios
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $studios = Studio::query()
            ->withCount('kursis')
            ->withCount('jadwals')
            ->when($search, function($query, $search) {
                return $query->where('nama_studio', 'like', "%{$search}%");
            })
            ->orderBy('nama_studio', 'asc')
            ->paginate(10);
        
        return view('admin.studios.index', compact('studios', 'search'));
    }

    /**
     * Show the form for creating a new studio
     */
    public function create()
    {
        return view('admin.studios.create');
    }

    /**
     * Store a newly created studio
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_studio' => 'required|string|max:50|unique:studios,nama_studio',
            'kapasitas' => 'required|integer|min:10|max:500',
        ]);

        $validated['created_by'] = Auth::id();

        DB::beginTransaction();
        try {
            // Create studio
            $studio = Studio::create($validated);

            // Auto generate kursi berdasarkan kapasitas
            $this->generateKursi($studio);

            DB::commit();

            return redirect()->route('admin.studios.index')
                ->with('success', 'Studio berhasil ditambahkan dengan ' . $studio->kapasitas . ' kursi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan studio: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified studio
     */
    public function show(Studio $studio)
    {
        $studio->load(['kursis', 'jadwals.film', 'createdBy']);
        
        // Get upcoming jadwals
        $upcomingJadwals = $studio->jadwals()
            ->where('tanggal_tayang', '>=', now())
            ->where('status_jadwal', 'Active')
            ->with('film')
            ->orderBy('tanggal_tayang', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->limit(5)
            ->get();
        
        return view('admin.studios.show', compact('studio', 'upcomingJadwals'));
    }

    /**
     * Show the form for editing the specified studio
     */
    public function edit(Studio $studio)
    {
        return view('admin.studios.edit', compact('studio'));
    }

    /**
     * Update the specified studio
     */
    public function update(Request $request, Studio $studio)
    {
        $validated = $request->validate([
            'nama_studio' => 'required|string|max:50|unique:studios,nama_studio,' . $studio->studio_id . ',studio_id',
            'kapasitas' => 'required|integer|min:10|max:500',
        ]);

        $oldKapasitas = $studio->kapasitas;
        $newKapasitas = $validated['kapasitas'];

        DB::beginTransaction();
        try {
            $studio->update($validated);

            // Jika kapasitas berubah, update kursi
            if ($oldKapasitas != $newKapasitas) {
                if ($newKapasitas > $oldKapasitas) {
                    // Tambah kursi
                    $this->addKursi($studio, $oldKapasitas, $newKapasitas);
                } else {
                    // Kurangi kursi (hapus kursi terakhir)
                    $this->removeKursi($studio, $newKapasitas);
                }
            }

            DB::commit();

            return redirect()->route('admin.studios.index')
                ->with('success', 'Studio berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate studio: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified studio
     */
    public function destroy(Studio $studio)
    {
        // Check if studio has active jadwals
        $activeJadwals = $studio->jadwals()
            ->where('status_jadwal', 'Active')
            ->where('tanggal_tayang', '>=', now())
            ->count();
        
        if ($activeJadwals > 0) {
            return redirect()->route('admin.studios.index')
                ->with('error', 'Studio tidak dapat dihapus karena masih memiliki ' . $activeJadwals . ' jadwal aktif!');
        }

        // Check if studio has jadwals with bookings
        $jadwalWithBookings = $studio->jadwals()
            ->whereHas('pemesanans', function($query) {
                $query->whereIn('status_pemesanan', ['Lunas', 'Menunggu Bayar']);
            })
            ->count();
        
        if ($jadwalWithBookings > 0) {
            return redirect()->route('admin.studios.index')
                ->with('error', 'Studio tidak dapat dihapus karena ada jadwal dengan pemesanan aktif!');
        }

        DB::beginTransaction();
        try {
            // Delete kursi first
            $studio->kursis()->delete();
            
            // Delete studio
            $studio->delete();

            DB::commit();

            return redirect()->route('admin.studios.index')
                ->with('success', 'Studio berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.studios.index')
                ->with('error', 'Gagal menghapus studio: ' . $e->getMessage());
        }
    }

    /**
     * Generate kursi untuk studio baru
     */
    private function generateKursi(Studio $studio)
    {
        $kapasitas = $studio->kapasitas;
        $kursiPerBaris = 10; // 10 kursi per baris
        $totalBaris = ceil($kapasitas / $kursiPerBaris);

        $kursiData = [];
        $counter = 1;

        for ($baris = 1; $baris <= $totalBaris; $baris++) {
            $hurufBaris = chr(64 + $baris); // A, B, C, D, ...

            for ($nomor = 1; $nomor <= $kursiPerBaris; $nomor++) {
                if ($counter > $kapasitas) break;

                $kursiData[] = [
                    'studio_id' => $studio->studio_id,
                    'kode_kursi' => $hurufBaris . $nomor,
                    'baris' => $hurufBaris,
                    'nomor_kursi' => $nomor,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $counter++;
            }
        }

        Kursi::insert($kursiData);
    }

    /**
     * Tambah kursi saat kapasitas ditambah
     */
    private function addKursi(Studio $studio, int $oldKapasitas, int $newKapasitas)
    {
        $jumlahTambah = $newKapasitas - $oldKapasitas;
        $kursiPerBaris = 10;
        
        // Get last kursi
        $lastKursi = $studio->kursis()->orderBy('kursi_id', 'desc')->first();
        
        if ($lastKursi) {
            $lastBaris = $lastKursi->baris;
            $lastNomor = $lastKursi->nomor_kursi;
            $barisNum = ord($lastBaris) - 64;
        } else {
            $lastBaris = 'A';
            $lastNomor = 0;
            $barisNum = 1;
        }

        $kursiData = [];
        for ($i = 1; $i <= $jumlahTambah; $i++) {
            $lastNomor++;
            
            if ($lastNomor > $kursiPerBaris) {
                $barisNum++;
                $lastNomor = 1;
                $lastBaris = chr(64 + $barisNum);
            }

            $kursiData[] = [
                'studio_id' => $studio->studio_id,
                'kode_kursi' => $lastBaris . $lastNomor,
                'baris' => $lastBaris,
                'nomor_kursi' => $lastNomor,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Kursi::insert($kursiData);
    }

    /**
     * Hapus kursi saat kapasitas dikurangi
     */
    private function removeKursi(Studio $studio, int $newKapasitas)
    {
        // Hapus kursi terakhir sampai sesuai kapasitas baru
        $studio->kursis()
            ->orderBy('kursi_id', 'desc')
            ->limit($studio->kursis()->count() - $newKapasitas)
            ->delete();
    }
}