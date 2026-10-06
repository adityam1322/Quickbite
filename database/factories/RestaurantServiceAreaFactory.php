<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\RestaurantServiceArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RestaurantServiceArea>
 */
class RestaurantServiceAreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'restaurant_id' => Restaurant::factory(),
            'area_name' => fake()->unique()->city(),
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'is_active' => true,
        ];
    }
}
