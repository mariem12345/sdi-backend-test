<?php


namespace Database\Factories;

use App\Models\Track;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackFactory extends Factory
{
    protected $model = Track::class;

    public function definition(): array
    {
        return [
            'spotify_id' => $this->faker->unique()->regexify('[A-Za-z0-9]{22}'),
            'name' => $this->faker->sentence(3),
            'duration_ms' => $this->faker->numberBetween(120000, 360000),
            'explicit' => $this->faker->boolean(),
            'popularity' => $this->faker->numberBetween(1, 100),
            'preview_url' => $this->faker->url(),
            'external_url' => $this->faker->url(),
            'artists' => [
                ['id' => $this->faker->regexify('[A-Za-z0-9]{22}'), 'name' => $this->faker->name()]
            ],
            'album' => [
                'name' => $this->faker->words(3, true),
                'images' => [['url' => $this->faker->imageUrl()]]
            ]
        ];
    }
}
