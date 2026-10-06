<?php

namespace Database\Factories;

use App\Models\Cuisine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cuisine>
 */
class CuisineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $name = fake()->randomElement([
            'American', 'Chinese', 'Indian', 'Italian', 'Japanese', 'Mexican',
        ]);

        return [
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'is_active' => true,
        ];
    }
}
