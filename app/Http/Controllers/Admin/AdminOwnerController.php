<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminOwnerController extends Controller
{
    /**
     * Display a listing of owners
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $owners = User::where('role', 'Owner')
            ->when($search, function($query, $search) {
                return $query->where('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('nama_lengkap', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.owners.index', compact('owners', 'search'));
    }

    /**
     * Show the form for creating a new owner
     */
    public function create()
    {
        return view('admin.owners.create');
    }

    /**
     * Store a newly created owner
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'nama_lengkap' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'Owner';

        User::create($validated);

        return redirect()->route('admin.owners.index')
            ->with('success', 'Owner berhasil ditambahkan!');
    }

    /**
     * Display the specified owner
     */
    public function show(User $owner)
    {
        // Pastikan user adalah owner
        if ($owner->role !== 'Owner') {
            abort(404);
        }
        
        return view('admin.owners.show', compact('owner'));
    }

    /**
     * Show the form for editing the specified owner
     */
    public function edit(User $owner)
    {
        // Pastikan user adalah owner
        if ($owner->role !== 'Owner') {
            abort(404);
        }

        return view('admin.owners.edit', compact('owner'));
    }

    /**
     * Update the specified owner
     */
    public function update(Request $request, User $owner)
    {
        // Pastikan user adalah owner
        if ($owner->role !== 'Owner') {
            abort(404);
        }

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $owner->user_id . ',user_id',
            'email' => 'required|email|max:100|unique:users,email,' . $owner->user_id . ',user_id',
            'nama_lengkap' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $owner->update($validated);

        return redirect()->route('admin.owners.index')
            ->with('success', 'Data owner berhasil diupdate!');
    }

    /**
     * Remove the specified owner
     */
    public function destroy(User $owner)
    {
        // Pastikan user adalah owner
        if ($owner->role !== 'Owner') {
            abort(404);
        }

        // Owner can be deleted (no relational constraints)
        $owner->delete();

        return redirect()->route('admin.owners.index')
            ->with('success', 'Owner berhasil dihapus!');
    }
}