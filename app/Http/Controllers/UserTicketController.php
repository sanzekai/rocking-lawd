<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function downloadPdf($ticket_code)
    {
        $ticket = Ticket::with(['event', 'order', 'user'])
            ->where('ticket_code', $ticket_code)
            ->where('user_id', auth()->id()) // Validasi kepemilikan tiket
            ->firstOrFail();

        $pdf = Pdf::loadView('tickets.pdf', compact('ticket'))
            ->setPaper('a6', 'portrait'); // Ukuran ringkas A6 untuk E-Ticket

        return $pdf->download('E-Ticket-' . $ticket->ticket_code . '.pdf');
    }
}
