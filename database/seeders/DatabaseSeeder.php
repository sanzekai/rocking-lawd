<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Role Spatie (Jika belum ada)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $organizerRole = Role::firstOrCreate(['name' => 'organizer']);
        $participantRole = Role::firstOrCreate(['name' => 'participant']);

        // 2. Buat Default User Organizer
        $organizer = User::firstOrCreate([
            'email' => 'organizer@rockinglawd.test',
        ], [
            'name' => 'Rocking Lawd Organizer',
            'password' => Hash::make('password'),
        ]);
        $organizer->assignRole($organizerRole);

        // 3. Buat Default Participant (Penonton)
        $participant = User::firstOrCreate([
            'email' => 'san@rockinglawd.test',
        ], [
            'name' => 'Ihsan Septiano',
            'password' => Hash::make('password'),
        ]);
        $participant->assignRole($participantRole);

        // 4. Seed Master Artist Hip-Hop
        $artist1 = Artist::firstOrCreate(
            ['slug' => 'tuan-tigabelas'],
            [
                'name' => 'Tuan Tigabelas',
                'genre' => 'Boom Bap / Hip-Hop',
                'bio' => 'Indonesian Rapper & MC',
            ]
        );

        $artist2 = Artist::firstOrCreate(
            ['slug' => 'laze'],
            [
                'name' => 'Laze',
                'genre' => 'Lyrical Hip-Hop',
                'bio' => 'Hip-Hop lyricist from Jakarta',
            ]
        );

        // 5. Seed Event Contoh
        $event = Event::firstOrCreate(
            ['slug' => 'rocking-lawd-vol-1-underground-cipher'],
            [
                'user_id' => $organizer->id,
                'title' => 'Rocking Lawd Vol. 1: Underground Cipher',
                'description' => 'Ajang pertunjukan musik hip-hop underground terbesar semester ini.',
                'venue' => 'Surabaya Hip-Hop Arena, East Java',
                'event_date' => now()->addDays(14),
                'total_quota' => 200,
                'remaining_quota' => 200,
                'price' => 75000,
                'is_published' => true,
            ]
        );

        // Attach Lineup Artist ke Event jika belum ada
        if (!$event->artists()->where('artist_id', $artist1->id)->exists()) {
            $event->artists()->attach($artist1->id, ['performance_stage' => 'Main Stage', 'performance_time' => '20:00:00']);
        }
        if (!$event->artists()->where('artist_id', $artist2->id)->exists()) {
            $event->artists()->attach($artist2->id, ['performance_stage' => 'Main Stage', 'performance_time' => '21:30:00']);
        }
    }
}
