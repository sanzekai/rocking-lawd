<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEvents = Event::count();
        $totalTicketsSold = Order::where('status', 'paid')->sum('quantity');
        $totalRevenue = Order::where('status', 'paid')->sum('total_price');
        $totalCheckedIn = Ticket::where('status', 'checked_in')->count();

        // Ambil event terbaru beserta statistik tiketnya
        $recentEvents = Event::withCount([
            'tickets',
            'tickets as checked_in_tickets_count' => function ($query) {
                $query->where('status', 'checked_in');
            }
        ])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalEvents',
            'totalTicketsSold',
            'totalRevenue',
            'totalCheckedIn',
            'recentEvents'
        ));
    }
}
