<?php

namespace Database\Factories;

use App\Models\RestaurantServiceArea;
use App\Models\Restaurant;
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
        'area_name' => fake()->city(),
        'city' => fake()->city(),
        'postal_code' => fake()->numerify('###-###'),
        'is_active' => fake()->boolean(),
        ];
    }
}
