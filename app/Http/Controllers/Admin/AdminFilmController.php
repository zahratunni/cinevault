<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminFilmController extends Controller
{
    /**
     * Display a listing of films
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        
        $films = Film::query()
            ->when($search, function($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                            ->orWhere('genre', 'like', "%{$search}%");
            })
            ->when($status, function($query, $status) {
                return $query->where('status_tayang', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.films.index', compact('films', 'search', 'status'));
    }

    /**
     * Show the form for creating a new film
     */
    public function create()
    {
        return view('admin.films.create');
    }

    /**
     * Store a newly created film
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'durasi_menit' => 'required|integer|min:30',
            'rating' => 'required|string|max:5',
            'genre' => 'required|string|max:50',
            'status_tayang' => 'required|in:Playing Now,Upcoming,Selesai',
            'poster' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'trailer_url' => 'nullable|url',
            'produser' => 'nullable|string|max:100',
            'sutradara' => 'nullable|string|max:100',
            'penulis' => 'nullable|string|max:100',
            'produksi' => 'nullable|string|max:100',
            'cast_list' => 'nullable|string',
        ]);

        // Handle Poster Upload
        if ($request->hasFile('poster')) {
            $poster = $request->file('poster');
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $poster->getClientOriginalExtension();
            $poster->move(public_path('posters'), $filename);
            $validated['poster_url'] = '/posters/' . $filename;
        }

        Film::create($validated);

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil ditambahkan!');
    }

    /**
     * Display the specified film
     */
    public function show(Film $film)
    {
        return view('admin.films.show', compact('film'));
    }

    /**
     * Show the form for editing the specified film
     */
    public function edit(Film $film)
    {
        return view('admin.films.edit', compact('film'));
    }

    /**
     * Update the specified film
     */
    public function update(Request $request, Film $film)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'durasi_menit' => 'required|integer|min:30',
            'rating' => 'required|string|max:5',
            'genre' => 'required|string|max:50',
            'status_tayang' => 'required|in:Playing Now,Upcoming,Selesai',
            'poster' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'trailer_url' => 'nullable|url',
            'produser' => 'nullable|string|max:100',
            'sutradara' => 'nullable|string|max:100',
            'penulis' => 'nullable|string|max:100',
            'produksi' => 'nullable|string|max:100',
            'cast_list' => 'nullable|string',
        ]);

        // Handle Poster Upload (if new poster uploaded)
        if ($request->hasFile('poster')) {
            // Delete old poster
            if ($film->poster_url) {
                $oldPoster = public_path($film->poster_url);
                if (file_exists($oldPoster)) {
                    unlink($oldPoster);
                }
            }

            // Upload new poster
            $poster = $request->file('poster');
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $poster->getClientOriginalExtension();
            $poster->move(public_path('posters'), $filename);
            $validated['poster_url'] = '/posters/' . $filename;
        }

        $film->update($validated);

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil diupdate!');
    }

    /**
     * Remove the specified film
     */
    public function destroy(Film $film)
    {
        // Delete poster file
        if ($film->poster_url) {
            $posterPath = public_path($film->poster_url);
            if (file_exists($posterPath)) {
                unlink($posterPath);
            }
        }

        $film->delete();

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil dihapus!');
    }
}