<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Film;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminJadwalController extends Controller
{
    /**
     * Display a listing of jadwals
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $film_id = $request->input('film_id');
        $studio_id = $request->input('studio_id');
        $status = $request->input('status');
        $tanggal = $request->input('tanggal');
        
        $jadwals = Jadwal::with(['film', 'studio'])
            ->when($search, function($query, $search) {
                return $query->whereHas('film', function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                });
            })
            ->when($film_id, function($query, $film_id) {
                return $query->where('film_id', $film_id);
            })
            ->when($studio_id, function($query, $studio_id) {
                return $query->where('studio_id', $studio_id);
            })
            ->when($status, function($query, $status) {
                return $query->where('status_jadwal', $status);
            })
            ->when($tanggal, function($query, $tanggal) {
                return $query->whereDate('tanggal_tayang', $tanggal);
            })
            ->orderBy('tanggal_tayang', 'desc')
            ->orderBy('jam_mulai', 'asc')
            ->paginate(15);
        
        // For filter dropdowns
        $films = Film::whereIn('status_tayang', ['Playing Now', 'Upcoming'])->get();
        $studios = Studio::all();
        
        return view('admin.jadwals.index', compact('jadwals', 'films', 'studios', 'search', 'film_id', 'studio_id', 'status', 'tanggal'));
    }

    /**
     * Show the form for creating a new jadwal
     */
    public function create()
    {
        $films = Film::whereIn('status_tayang', ['Playing Now', 'Upcoming'])->get();
        $studios = Studio::all();
        
        return view('admin.jadwals.create', compact('films', 'studios'));
    }

    /**
     * Store a newly created jadwal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,film_id',
            'studio_id' => 'required|exists:studios,studio_id',
            'tanggal_tayang' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'harga_reguler' => 'required|numeric|min:0',
            'status_jadwal' => 'required|in:Active,Canceled,Full',
        ]);

        // Get film to calculate jam_selesai
        $film = Film::findOrFail($request->film_id);
        
        // Calculate jam_selesai (jam_mulai + durasi film + 15 menit buffer)
        $jamMulai = Carbon::parse($request->tanggal_tayang . ' ' . $request->jam_mulai);
        $jamSelesai = $jamMulai->copy()->addMinutes($film->durasi_menit + 15);
        
        $validated['jam_selesai'] = $jamSelesai->format('H:i:s');
        $validated['created_by'] = Auth::id();

        // Check for schedule conflicts
        $conflict = Jadwal::where('studio_id', $request->studio_id)
            ->where('tanggal_tayang', $request->tanggal_tayang)
            ->where('status_jadwal', '!=', 'Canceled')
            ->where(function($query) use ($jamMulai, $jamSelesai) {
                $query->whereBetween('jam_mulai', [$jamMulai->format('H:i:s'), $jamSelesai->format('H:i:s')])
                      ->orWhereBetween('jam_selesai', [$jamMulai->format('H:i:s'), $jamSelesai->format('H:i:s')])
                      ->orWhere(function($q) use ($jamMulai, $jamSelesai) {
                          $q->where('jam_mulai', '<=', $jamMulai->format('H:i:s'))
                            ->where('jam_selesai', '>=', $jamSelesai->format('H:i:s'));
                      });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jadwal bentrok! Studio sudah digunakan pada waktu tersebut.');
        }

        Jadwal::create($validated);

        return redirect()->route('admin.jadwals.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Display the specified jadwal
     */
    public function show(Jadwal $jadwal)
    {
        $jadwal->load(['film', 'studio', 'pemesanans.user', 'createdBy']);
        
        return view('admin.jadwals.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified jadwal
     */
    public function edit(Jadwal $jadwal)
    {
        $films = Film::whereIn('status_tayang', ['Playing Now', 'Upcoming'])->get();
        $studios = Studio::all();
        
        return view('admin.jadwals.edit', compact('jadwal', 'films', 'studios'));
    }

    /**
     * Update the specified jadwal
     */
    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,film_id',
            'studio_id' => 'required|exists:studios,studio_id',
            'tanggal_tayang' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'harga_reguler' => 'required|numeric|min:0',
            'status_jadwal' => 'required|in:Active,Canceled,Full',
        ]);

        // Get film to calculate jam_selesai
        $film = Film::findOrFail($request->film_id);
        
        // Calculate jam_selesai
        $jamMulai = Carbon::parse($request->tanggal_tayang . ' ' . $request->jam_mulai);
        $jamSelesai = $jamMulai->copy()->addMinutes($film->durasi_menit + 15);
        
        $validated['jam_selesai'] = $jamSelesai->format('H:i:s');

        // Check for schedule conflicts (exclude current jadwal)
        $conflict = Jadwal::where('studio_id', $request->studio_id)
            ->where('tanggal_tayang', $request->tanggal_tayang)
            ->where('jadwal_id', '!=', $jadwal->jadwal_id)
            ->where('status_jadwal', '!=', 'Canceled')
            ->where(function($query) use ($jamMulai, $jamSelesai) {
                $query->whereBetween('jam_mulai', [$jamMulai->format('H:i:s'), $jamSelesai->format('H:i:s')])
                      ->orWhereBetween('jam_selesai', [$jamMulai->format('H:i:s'), $jamSelesai->format('H:i:s')])
                      ->orWhere(function($q) use ($jamMulai, $jamSelesai) {
                          $q->where('jam_mulai', '<=', $jamMulai->format('H:i:s'))
                            ->where('jam_selesai', '>=', $jamSelesai->format('H:i:s'));
                      });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jadwal bentrok! Studio sudah digunakan pada waktu tersebut.');
        }

        $jadwal->update($validated);

        return redirect()->route('admin.jadwals.index')
            ->with('success', 'Jadwal berhasil diupdate!');
    }

    /**
     * Remove the specified jadwal
     */
    public function destroy(Jadwal $jadwal)
    {
        // Check if jadwal has bookings
        $bookingCount = $jadwal->pemesanans()
            ->whereIn('status_pemesanan', ['Lunas', 'Menunggu Bayar'])
            ->count();
        
        if ($bookingCount > 0) {
            return redirect()->route('admin.jadwals.index')
                ->with('error', 'Jadwal tidak dapat dihapus karena ada ' . $bookingCount . ' pemesanan aktif!');
        }

        $jadwal->delete();

        return redirect()->route('admin.jadwals.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}