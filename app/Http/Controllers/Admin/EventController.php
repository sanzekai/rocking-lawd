<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Artist;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $events = Event::with(['user', 'artists'])->latest()->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $artists = Artist::orderBy('name')->get();

        return view('admin.events.create', compact('artists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $bannerPath = null;
            if ($request->hasFile('banner')) {
                $bannerPath = $request->file('banner')->store('events', 'public');
            }

            // 1. Simpan Master Event
            $event = Event::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'slug' => Str::slug($request->title).'-'.Str::random(5),
                'description' => $request->description,
                'banner_path' => $bannerPath,
                'venue' => $request->venue,
                'event_date' => $request->event_date,
                'total_quota' => $request->total_quota,
                'remaining_quota' => $request->total_quota, // Initial sama dengan total
                'price' => $request->price,
                'is_published' => true,
            ]);

            // 2. Format Data Pivot Artist
            $syncData = [];
            foreach ($request->artist_ids as $index => $artistId) {
                $syncData[$artistId] = [
                    'performance_stage' => $request->performance_stages[$index] ?? 'Main Stage',
                    'performance_time' => $request->performance_times[$index] ?? null,
                ];
            }

            // 3. Attach ke Tabel Pivot `artist_event`
            $event->artists()->attach($syncData);
        });

        return redirect()->route('admin.events.index')->with('success', 'Event Rocking Lawd berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        $event->load(['artists', 'user']);

        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        $event->load('artists');
        $artists = Artist::orderBy('name')->get();

        return view('admin.events.edit', compact('event', 'artists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        DB::transaction(function () use ($request, $event) {
            $bannerPath = $event->banner_path;
            if ($request->hasFile('banner')) {
                if ($bannerPath && Storage::disk('public')->exists($bannerPath)) {
                    Storage::disk('public')->delete($bannerPath);
                }
                $bannerPath = $request->file('banner')->store('events', 'public');
            }

            $diffQuota = $request->total_quota - $event->total_quota;
            $newRemainingQuota = max(0, $event->remaining_quota + $diffQuota);

            $event->update([
                'title' => $request->title,
                'description' => $request->description,
                'banner_path' => $bannerPath,
                'venue' => $request->venue,
                'event_date' => $request->event_date,
                'total_quota' => $request->total_quota,
                'remaining_quota' => $newRemainingQuota,
                'price' => $request->price,
                'is_published' => $request->boolean('is_published', true),
            ]);

            $syncData = [];
            foreach ($request->artist_ids as $index => $artistId) {
                $syncData[$artistId] = [
                    'performance_stage' => $request->performance_stages[$index] ?? 'Main Stage',
                    'performance_time' => $request->performance_times[$index] ?? null,
                ];
            }

            $event->artists()->sync($syncData);
        });

        return redirect()->route('admin.events.index')->with('success', 'Event Rocking Lawd berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        if ($event->banner_path && Storage::disk('public')->exists($event->banner_path)) {
            Storage::disk('public')->delete($event->banner_path);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
    }
}
