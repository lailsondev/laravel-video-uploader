<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->words(3, true);

        return [
            'title' => $title,
            'code' => $this->faker->uuid(),
            'description' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'slug' => str($title)->slug(),
            'type' => 'MOVIE',
        ];
    }

    public function series()
    {
        return $this->state(fn (array $attributes) => ['type' => 'SERIE']);
    }
}
