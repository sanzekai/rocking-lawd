<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArtistRequest;
use App\Http\Requests\UpdateArtistRequest;
use App\Models\Artist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $artists = Artist::withCount('events')->latest()->paginate(10);

        return view('admin.artists.index', compact('artists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.artists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArtistRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $slug = Str::slug($data['name']);
        if (Artist::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::lower(Str::random(5));
        }
        $data['slug'] = $slug;

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('artists', 'public');
        }

        unset($data['photo']);

        Artist::create($data);

        return redirect()->route('admin.artists.index')->with('success', 'Artist berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Artist $artist): View
    {
        $artist->load('events');

        return view('admin.artists.show', compact('artist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artist $artist): View
    {
        return view('admin.artists.edit', compact('artist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArtistRequest $request, Artist $artist): RedirectResponse
    {
        $data = $request->validated();

        if ($data['name'] !== $artist->name) {
            $slug = Str::slug($data['name']);
            if (Artist::where('slug', $slug)->where('id', '!=', $artist->id)->exists()) {
                $slug .= '-'.Str::lower(Str::random(5));
            }
            $data['slug'] = $slug;
        }

        if ($request->hasFile('photo')) {
            if ($artist->photo_path && Storage::disk('public')->exists($artist->photo_path)) {
                Storage::disk('public')->delete($artist->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('artists', 'public');
        }

        unset($data['photo']);

        $artist->update($data);

        return redirect()->route('admin.artists.index')->with('success', 'Artist berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist): RedirectResponse
    {
        if ($artist->photo_path && Storage::disk('public')->exists($artist->photo_path)) {
            Storage::disk('public')->delete($artist->photo_path);
        }

        $artist->delete();

        return redirect()->route('admin.artists.index')->with('success', 'Artist berhasil dihapus!');
    }
}
