<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class UserTicketController extends Controller
{
    public function index()
    {
        // Ambil semua tiket milik penonton yang sedang login
        $tickets = Ticket::with(['event', 'order'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets'));
    }
}
