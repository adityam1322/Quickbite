<?php

namespace Database\Factories;

use App\Models\Cuisine;
use Illuminate\Database\Support\Str;
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

        $name = fake()->name();


        return [
        'name' => $name,
        'slug' => strtolower(str_replace(' ', '_', $name)),
        'is_active' => fake()->boolean(),
        ];
    }
}
