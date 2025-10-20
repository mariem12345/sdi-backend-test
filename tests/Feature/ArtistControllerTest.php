<?php

namespace Tests\Feature;

use App\Models\Artist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtistControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_artists()
    {
        Artist::factory()->count(3)->create();

        $response = $this->getJson('/api/artists');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'message',
                'pagination' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page'
                ]
            ]);
    }

    /** @test */
    public function it_can_create_an_artist()
    {
        $artistData = [
            'spotify_id' => 'test_spotify_id_123',
            'name' => 'New Artist',
            'genres' => ['pop', 'rock'],
            'popularity' => 85,
            'followers' => 1000000,
            'image_url' => 'https://example.com/image.jpg',
            'spotify_url' => 'https://open.spotify.com/artist/test123'
        ];

        $response = $this->postJson('/api/artists', $artistData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Artist created successfully'
            ]);

        $this->assertDatabaseHas('artists', [
            'spotify_id' => 'test_spotify_id_123',
            'name' => 'New Artist'
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_artist()
    {
        $response = $this->postJson('/api/artists', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['spotify_id', 'name', 'genres']);
    }

    /** @test */
    public function it_can_show_a_specific_artist()
    {
        $artist = Artist::factory()->create();

        $response = $this->getJson("/api/artists/{$artist->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $artist->id,
                    'name' => $artist->name
                ],
                'message' => 'Artist retrieved successfully'
            ]);
    }

    /** @test */
    public function it_returns_404_when_artist_not_found()
    {
        $response = $this->getJson('/api/artists/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_an_artist()
    {
        $artist = Artist::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/artists/{$artist->id}", [
            'name' => 'Updated Name',
            'popularity' => 90
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Artist updated successfully'
            ]);

        $this->assertDatabaseHas('artists', [
            'id' => $artist->id,
            'name' => 'Updated Name',
            'popularity' => 90
        ]);
    }

    /** @test */
    public function it_can_delete_an_artist()
    {
        $artist = Artist::factory()->create();

        $response = $this->deleteJson("/api/artists/{$artist->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Artist deleted successfully'
            ]);

        $this->assertDatabaseMissing('artists', ['id' => $artist->id]);
    }

    /** @test */
    public function it_can_search_artists_by_name()
    {
        Artist::factory()->create(['name' => 'Pitbull']);
        Artist::factory()->create(['name' => 'Ed Sheeran']);

        $response = $this->getJson('/api/artists?search=Pitbull');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_can_filter_artists_by_popularity_range()
    {
        Artist::factory()->create(['popularity' => 70]);
        Artist::factory()->create(['popularity' => 85]);
        Artist::factory()->create(['popularity' => 95]);

        $response = $this->getJson('/api/artists/popularity/range?min=80&max=90');

        $response->assertStatus(200);

        $artists = $response->json('data');
        $this->assertCount(1, $artists);
        $this->assertEquals(85, $artists[0]['popularity']);
    }

    /** @test */
    public function it_validates_popularity_range_parameters()
    {
        $response = $this->getJson('/api/artists/popularity/range');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['min', 'max']);
    }

    /** @test */
    public function it_can_paginate_artists()
    {
        Artist::factory()->count(25)->create();

        $response = $this->getJson('/api/artists?per_page=10&page=2');

        $response->assertStatus(200);

        $pagination = $response->json('pagination');
        $this->assertEquals(2, $pagination['current_page']);
        $this->assertEquals(10, $pagination['per_page']);
        $this->assertEquals(25, $pagination['total']);
    }
}
