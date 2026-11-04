<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:5', // Batasi max 5 tiket per transaksi
        ]);

        try {
            DB::transaction(function () use ($event, $request) {
                // 1. Penguncian baris database (Locking) untuk mencegah race condition kuota
                $eventRecord = Event::where('id', $event->id)->lockForUpdate()->first();

                if ($eventRecord->remaining_quota < $request->quantity) {
                    throw new \Exception('Maaf, sisa kuota tiket tidak mencukupi!');
                }

                $totalPrice = $eventRecord->price * $request->quantity;
                $orderNumber = 'ORD-' . strtoupper(now()->format('Ymd')) . '-' . strtoupper(Str::random(4));

                // 2. Buat Record Transaksi Utama (Tabel `orders`)
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => auth()->id(),
                    'event_id' => $eventRecord->id,
                    'quantity' => $request->quantity,
                    'total_price' => $totalPrice,
                    'status' => 'paid', // Karena simulasi langsung paid (atau pending jika gratis)
                    'paid_at' => now(),
                ]);

                // 3. Trigger Pembentukan Record Tiket (Tabel `tickets` sebanyak quantity)
                for ($i = 0; $i < $request->quantity; $i++) {
                    Ticket::create([
                        'order_id' => $order->id,
                        'event_id' => $eventRecord->id,
                        'user_id' => auth()->id(),
                        'ticket_code' => 'RL-' . strtoupper(Str::random(8)),
                        'status' => 'registered',
                    ]);
                }

                // 4. Potong Kuota Event
                $eventRecord->decrement('remaining_quota', $request->quantity);
            });

            return redirect()->route('my-tickets.index')->with('success', 'Transaksi berhasil! Tiket kamu sudah terbit.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
