<?php

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Artist::create([
            'spotify_id' => '0TnOYISbd1XYRBk9myaseg',
            'name' => 'Pitbull',
            'genres' => ['pop', 'latin', 'dance'],
            'popularity' => 85,
            'followers' => 15872345,
            'image_url' => 'https://i.scdn.co/image/ab6761610000e5ebd42a27db3286b58553da8858',
            'spotify_url' => 'https://open.spotify.com/artist/0TnOYISbd1XYRBk9myaseg',
        ]);

        Artist::create([
            'spotify_id' => '1vCWHaC5f2uS3yhpwWbIA6',
            'name' => 'Avicii',
            'genres' => ['electronic', 'dance', 'pop'],
            'popularity' => 90,
            'followers' => 25000000,
            'image_url' => 'https://i.scdn.co/image/ab6761610000e5eb5980c9d2c431351f24771ab5',
            'spotify_url' => 'https://open.spotify.com/artist/1vCWHaC5f2uS3yhpwWbIA6',
        ]);

        Artist::create([
            'spotify_id' => '6eUKZXaKkcviH0Ku9w2n3V',
            'name' => 'Ed Sheeran',
            'genres' => ['pop', 'singer-songwriter', 'acoustic'],
            'popularity' => 95,
            'followers' => 45000000,
            'image_url' => 'https://i.scdn.co/image/ab6761610000e5eb9e3ac31b5d2e7c7c7a0f4c0e',
            'spotify_url' => 'https://open.spotify.com/artist/6eUKZXaKkcviH0Ku9w2n3V',
        ]);
    }
}
