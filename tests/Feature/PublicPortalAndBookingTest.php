<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPortalAndBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_published_events(): void
    {
        $organizer = User::factory()->create();
        $publishedEvent = Event::create([
            'user_id' => $organizer->id,
            'title' => 'Rock Festival 2026',
            'slug' => 'rock-festival-2026',
            'description' => 'Fest musik cadas.',
            'venue' => 'Gelora Bung Karno',
            'event_date' => now()->addDays(10),
            'total_quota' => 100,
            'remaining_quota' => 100,
            'price' => 250000,
            'is_published' => true,
        ]);

        $unpublishedEvent = Event::create([
            'user_id' => $organizer->id,
            'title' => 'Secret Gig',
            'slug' => 'secret-gig',
            'description' => 'Belum rilis.',
            'venue' => 'Underground',
            'event_date' => now()->addDays(5),
            'total_quota' => 50,
            'remaining_quota' => 50,
            'price' => 100000,
            'is_published' => false,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Rock Festival 2026');
        $response->assertDontSee('Secret Gig');
    }

    public function test_event_detail_page_displays_details_and_artists(): void
    {
        $organizer = User::factory()->create();
        $event = Event::create([
            'user_id' => $organizer->id,
            'title' => 'Indie Fest 2026',
            'slug' => 'indie-fest-2026',
            'description' => 'Festival musik indie.',
            'venue' => 'Parkir Timur Senayan',
            'event_date' => now()->addDays(7),
            'total_quota' => 50,
            'remaining_quota' => 50,
            'price' => 150000,
            'is_published' => true,
        ]);

        $artist = Artist::create([
            'name' => 'The Jansen',
            'slug' => 'the-jansen',
            'genre' => 'Punk Rock',
            'bio' => 'Indonesian punk band.',
        ]);

        $event->artists()->attach($artist->id, [
            'performance_stage' => 'Thunder Stage',
            'performance_time' => '20:00:00',
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee('Indie Fest 2026');
        $response->assertSee('The Jansen');
        $response->assertSee('Thunder Stage');
    }

    public function test_user_can_checkout_tickets_with_atomic_locking_and_quota_decrement(): void
    {
        $user = User::factory()->create();
        $organizer = User::factory()->create();

        $event = Event::create([
            'user_id' => $organizer->id,
            'title' => 'Metal Summit',
            'slug' => 'metal-summit',
            'description' => 'Konser metal tahunan.',
            'venue' => 'Stadion Madya',
            'event_date' => now()->addDays(14),
            'total_quota' => 10,
            'remaining_quota' => 10,
            'price' => 300000,
            'is_published' => true,
        ]);

        $response = $this->actingAs($user)->post(route('orders.checkout', $event->id), [
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('my-tickets.index'));
        $response->assertSessionHas('success');

        $this->assertEquals(8, $event->fresh()->remaining_quota);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'quantity' => 2,
            'total_price' => 600000,
            'status' => 'paid',
        ]);

        $this->assertDatabaseCount('tickets', 2);
        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'registered',
        ]);
    }

    public function test_checkout_fails_when_quantity_exceeds_quota(): void
    {
        $user = User::factory()->create();
        $organizer = User::factory()->create();

        $event = Event::create([
            'user_id' => $organizer->id,
            'title' => 'Limited Session',
            'slug' => 'limited-session',
            'description' => 'Sesi terbatas.',
            'venue' => 'Intimate Hall',
            'event_date' => now()->addDays(2),
            'total_quota' => 2,
            'remaining_quota' => 1,
            'price' => 500000,
            'is_published' => true,
        ]);

        $response = $this->actingAs($user)->from(route('events.show', $event->slug))->post(route('orders.checkout', $event->id), [
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('events.show', $event->slug));
        $response->assertSessionHas('error', 'Maaf, sisa kuota tiket tidak mencukupi!');
        $this->assertEquals(1, $event->fresh()->remaining_quota);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_user_can_view_my_tickets_with_qr_code(): void
    {
        $user = User::factory()->create();
        $organizer = User::factory()->create();

        $event = Event::create([
            'user_id' => $organizer->id,
            'title' => 'Acoustic Night',
            'slug' => 'acoustic-night',
            'description' => 'Malam syahdu.',
            'venue' => 'Amphitheater',
            'event_date' => now()->addDays(3),
            'total_quota' => 20,
            'remaining_quota' => 20,
            'price' => 100000,
            'is_published' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-20261104-TEST',
            'user_id' => $user->id,
            'event_id' => $event->id,
            'quantity' => 1,
            'total_price' => 100000,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $ticket = Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'user_id' => $user->id,
            'ticket_code' => 'RL-TEST1234',
            'status' => 'registered',
        ]);

        $response = $this->actingAs($user)->get(route('my-tickets.index'));

        $response->assertStatus(200);
        $response->assertSee('RL-TEST1234');
        $response->assertSee('ORD-20261104-TEST');
        $response->assertSee('Acoustic Night');
    }
}
