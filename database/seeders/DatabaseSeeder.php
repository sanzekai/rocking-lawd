<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Buat Role organizer dan participant
        $organizerRole = Role::firstOrCreate(['name' => 'organizer']);
        $participantRole = Role::firstOrCreate(['name' => 'participant']);

        // 3. Buat User dummy untuk organizer@rockinglawd.test dan penonton@rockinglawd.test
        $organizerUser = User::firstOrCreate(
            ['email' => 'organizer@rockinglawd.test'],
            [
                'name' => 'Organizer Rocking Lawd',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $organizerUser->assignRole($organizerRole);

        $participantUser = User::firstOrCreate(
            ['email' => 'penonton@rockinglawd.test'],
            [
                'name' => 'Penonton Rocking Lawd',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $participantUser->assignRole($participantRole);

        // 4. Insert 3 master Artist ("Basboi", "A Nayaka", "Tuan Tigabelas")
        $artists = [
            [
                'name' => 'Basboi',
                'slug' => Str::slug('Basboi'),
                'genre' => 'Hip-Hop',
                'bio' => 'Basboi adalah musisi dan rapper hip-hop asal Bandung, Indonesia.',
                'photo_path' => null,
            ],
            [
                'name' => 'A Nayaka',
                'slug' => Str::slug('A Nayaka'),
                'genre' => 'Hip-Hop',
                'bio' => 'Ariel Nayaka (A. Nayaka) adalah rapper ternama asal Jakarta, Indonesia.',
                'photo_path' => null,
            ],
            [
                'name' => 'Tuan Tigabelas',
                'slug' => Str::slug('Tuan Tigabelas'),
                'genre' => 'Hip-Hop',
                'bio' => 'Tuan Tigabelas adalah musisi hip-hop dan aktivis asal Indonesia.',
                'photo_path' => null,
            ],
        ];

        foreach ($artists as $artistData) {
            Artist::updateOrCreate(
                ['slug' => $artistData['slug']],
                $artistData
            );
        }
    }
}
