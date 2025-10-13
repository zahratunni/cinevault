<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Jadwal;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FilmController extends Controller
{
  public function show($id)
{
    $today = Carbon::today();
    $tomorrow = Carbon::tomorrow();

    $film = Film::findOrFail($id);

    // Ambil semua jadwal hari ini dan besok
    $jadwals = Jadwal::where('film_id', $id)
        ->where('status_jadwal', 'Active')
        ->whereIn('tanggal_tayang', [$today->toDateString(), $tomorrow->toDateString()])
        ->orderBy('tanggal_tayang', 'asc')
        ->orderBy('jam_mulai', 'asc')
        ->with('studio')
        ->get();

    // Kelompokkan jadwal berdasarkan tanggal
    $jadwalsByDate = $jadwals->groupBy('tanggal_tayang');

    // Filter jadwal hari ini saja
    $jadwalHariIni = $jadwals->where('tanggal_tayang', $today->toDateString());

    return view('films.show', compact('film', 'jadwalsByDate', 'jadwalHariIni', 'today', 'tomorrow'));
}

    public function playingNow()
    {
        $films = Film::where('status_tayang', 'Playing Now')->get();
        return view('films.playing-now', compact('films'));
    }

    public function upcoming()
    {
        $films = Film::where('status_tayang', 'Upcoming')->get();
        return view('films.upcoming', compact('films'));
    }

   public function index(Request $request)
{
    $search = $request->input('search');

    $films = Film::whereIn('status_tayang', ['Playing Now', 'Upcoming'])
        ->when($search, function ($query) use ($search) {
            $query->where('judul', 'like', "%{$search}%");
        })
        ->get();

    return view('films.index', compact('films', 'search'));
}


}