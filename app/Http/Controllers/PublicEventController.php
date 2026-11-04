<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function show(string $slug)
    {
        $event = Event::with('artists')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('events.show', compact('event'));
    }
}
