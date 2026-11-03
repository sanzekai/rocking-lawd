<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminArtistAndEventTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'organizer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('organizer');

        $this->regularUser = User::factory()->create();
        $this->regularUser->assignRole('customer');
    }

    private function createFakeJpeg(string $filename): UploadedFile
    {
        $jpegData = base64_decode('/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA=');
        $tempPath = tempnam(sys_get_temp_dir(), 'test_img_');
        file_put_contents($tempPath, $jpegData);

        return new UploadedFile($tempPath, $filename, 'image/jpeg', null, true);
    }

    public function test_guests_and_customers_cannot_access_admin_routes(): void
    {
        $this->get(route('admin.artists.index'))->assertRedirect(route('login'));
        $this->get(route('admin.events.index'))->assertRedirect(route('login'));

        $this->actingAs($this->regularUser)
            ->get(route('admin.artists.index'))
            ->assertForbidden();

        $this->actingAs($this->regularUser)
            ->get(route('admin.events.index'))
            ->assertForbidden();
    }

    public function test_organizer_can_create_artist_with_photo_and_auto_slug(): void
    {
        Storage::fake('public');

        $photo = $this->createFakeJpeg('kendrick.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.artists.store'), [
            'name' => 'Kendrick Lamar',
            'genre' => 'West Coast Hip-Hop',
            'photo' => $photo,
            'bio' => 'Pulitzer-winning Compton rapper.',
        ]);

        $response->assertRedirect(route('admin.artists.index'));

        $this->assertDatabaseHas('artists', [
            'name' => 'Kendrick Lamar',
            'slug' => 'kendrick-lamar',
            'genre' => 'West Coast Hip-Hop',
            'bio' => 'Pulitzer-winning Compton rapper.',
        ]);

        $artist = Artist::first();
        $this->assertNotNull($artist->photo_path);
        Storage::disk('public')->assertExists($artist->photo_path);
    }

    public function test_organizer_can_update_and_delete_artist(): void
    {
        Storage::fake('public');

        $photo = $this->createFakeJpeg('saykoji.jpg');
        $artist = Artist::create([
            'name' => 'Saykoji',
            'slug' => 'saykoji',
            'genre' => 'Indonesian Hip-Hop',
            'photo_path' => $photo->store('artists', 'public'),
        ]);

        Storage::disk('public')->assertExists($artist->photo_path);

        $newPhoto = $this->createFakeJpeg('saykoji_new.jpg');
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.artists.update', $artist), [
            'name' => 'Saykoji Updated',
            'genre' => 'Boom Bap',
            'photo' => $newPhoto,
            'bio' => 'Updated bio.',
        ]);

        $updateResponse->assertRedirect(route('admin.artists.index'));
        $artist->refresh();
        $this->assertEquals('Saykoji Updated', $artist->name);
        $this->assertEquals('saykoji-updated', $artist->slug);
        Storage::disk('public')->assertExists($artist->photo_path);

        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.artists.destroy', $artist));
        $deleteResponse->assertRedirect(route('admin.artists.index'));
        $this->assertDatabaseMissing('artists', ['id' => $artist->id]);
        Storage::disk('public')->assertMissing($artist->photo_path);
    }

    public function test_organizer_can_create_event_with_multi_artist_lineup_and_banner(): void
    {
        Storage::fake('public');

        $artist1 = Artist::create(['name' => 'Iwa K', 'slug' => 'iwa-k', 'genre' => 'Old School']);
        $artist2 = Artist::create(['name' => 'Basboi', 'slug' => 'basboi', 'genre' => 'Modern Hip-Hop']);

        $banner = $this->createFakeJpeg('festival_poster.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.events.store'), [
            'title' => 'Rocking Lawd Fest 2026',
            'description' => 'A premier Indonesian hip-hop festival gathering the legends.',
            'venue' => 'Lapangan Pussenif, Bandung',
            'event_date' => now()->addDays(14)->format('Y-m-d H:i:s'),
            'total_quota' => 500,
            'price' => 250000,
            'banner' => $banner,
            'artist_ids' => [$artist1->id, $artist2->id],
            'performance_stages' => ['Main Stage', 'Cypher Ring'],
            'performance_times' => ['21:00', '19:30'],
        ]);

        $response->assertRedirect(route('admin.events.index'));

        $this->assertDatabaseHas('events', [
            'title' => 'Rocking Lawd Fest 2026',
            'venue' => 'Lapangan Pussenif, Bandung',
            'total_quota' => 500,
            'remaining_quota' => 500,
            'price' => 250000,
            'is_published' => 1,
        ]);

        $event = Event::first();
        $this->assertNotNull($event->banner_path);
        Storage::disk('public')->assertExists($event->banner_path);

        $this->assertCount(2, $event->artists);
        $this->assertDatabaseHas('artist_event', [
            'event_id' => $event->id,
            'artist_id' => $artist1->id,
            'performance_stage' => 'Main Stage',
            'performance_time' => '21:00',
        ]);
        $this->assertDatabaseHas('artist_event', [
            'event_id' => $event->id,
            'artist_id' => $artist2->id,
            'performance_stage' => 'Cypher Ring',
            'performance_time' => '19:30',
        ]);
    }
}
