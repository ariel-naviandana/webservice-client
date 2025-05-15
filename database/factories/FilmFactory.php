<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genres = ['Action', 'Comedy', 'Drama', 'Horror', 'Romance', 'Sci-Fi', 'Adventure', 'Thriller'];

        return [
            'title' => $this->faker->sentence(3),
            'synopsis' => Str::limit($this->faker->paragraph(), 255, ''),
            'genre' => $this->faker->randomElement($genres),
            'director' => $this->faker->name(),
            'release_date' => $this->faker->date(),
            'poster_url' => $this->faker->imageUrl(160, 240, 'film', true),
        ];
    }
}
