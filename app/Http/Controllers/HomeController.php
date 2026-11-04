<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::with('artists')
            ->where('is_published', true)
            ->latest('event_date')
            ->get();

        return view('home', compact('events'));
    }
}
