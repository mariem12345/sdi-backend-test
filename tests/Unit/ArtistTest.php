<?php

namespace Tests\Unit;

use App\Models\Artist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ArtistTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_an_artist()
    {
        $artist = Artist::factory()->create([
            'name' => 'Test Artist',
            'spotify_id' => 'test123'
        ]);

        $this->assertInstanceOf(Artist::class, $artist);
        $this->assertEquals('Test Artist', $artist->name);
        $this->assertEquals('test123', $artist->spotify_id);
    }

    #[Test]
    public function it_can_cast_genres_to_array()
    {
        $genres = ['pop', 'rock', 'electronic'];

        $artist = Artist::factory()->create([
            'genres' => $genres
        ]);

        $this->assertIsArray($artist->genres);
        $this->assertEquals($genres, $artist->genres);
    }

    #[Test]
    public function it_can_search_artists_by_name()
    {
        Artist::factory()->create(['name' => 'Pitbull']);
        Artist::factory()->create(['name' => 'Ed Sheeran']);
        Artist::factory()->create(['name' => 'Taylor Swift']);

        $results = Artist::search('pitbull')->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Pitbull', $results->first()->name);
    }

    #[Test]
    public function it_can_filter_artists_by_genre()
    {
        Artist::factory()->create(['genres' => ['pop', 'dance']]);
        Artist::factory()->create(['genres' => ['rock', 'metal']]);
        Artist::factory()->create(['genres' => ['pop', 'r&b']]);

        $results = Artist::byGenre('pop')->get();

        $this->assertCount(2, $results);
    }
}
