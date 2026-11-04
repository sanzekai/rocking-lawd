<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index(Event $event)
    {
        return view('admin.scanner.index', compact('event'));
    }

    public function processCheckIn(Request $request, Event $event)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $ticket = Ticket::with('user')
            ->where('ticket_code', trim($request->ticket_code))
            ->where('event_id', $event->id)
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'TIKET INVALID! Kode tidak terdaftar.',
            ], 404);
        }

        if ($ticket->status === 'checked_in') {
            return response()->json([
                'status' => 'warning',
                'message' => 'TIKET SUDAH DIPAKAI pada ' . $ticket->checked_in_at->format('H:i, d M Y'),
                'participant' => $ticket->user->name,
            ], 400);
        }

        $ticket->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
            'checked_in_by' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'CHECK-IN BERHASIL!',
            'participant' => $ticket->user->name,
            'ticket_code' => $ticket->ticket_code,
        ]);
    }
}
