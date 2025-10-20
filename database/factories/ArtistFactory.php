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
            'name' => $this->faker->name(),
            'genres' => $this->faker->randomElements(['rock', 'pop', 'jazz', 'classical'], 2),
            'popularity' => $this->faker->numberBetween(1, 100),
            'followers' => $this->faker->numberBetween(1000, 1000000),
            'external_url' => $this->faker->url(),
            'images' => [
                ['url' => $this->faker->imageUrl(), 'height' => 300, 'width' => 300]
            ]
        ];
    }
}
