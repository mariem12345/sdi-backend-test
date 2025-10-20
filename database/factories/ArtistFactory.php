<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtistFactory extends Factory
{
    protected $model = Artist::class;

    public function definition(): array
    {
        return [
            'spotify_id' => $this->faker->unique()->regexify('[A-Za-z0-9]{22}'),
            'name' => $this->faker->name,
            'genres' => $this->faker->randomElements(['pop', 'rock', 'jazz', 'electronic', 'hip-hop', 'r&b'], 2),
            'popularity' => $this->faker->numberBetween(0, 100),
            'followers' => $this->faker->numberBetween(1000, 10000000),
            'image_url' => $this->faker->imageUrl(),
            'spotify_url' => 'https://open.spotify.com/artist/' . $this->faker->regexify('[A-Za-z0-9]{22}'),
        ];
    }
}
