<?php

namespace Database\Factories;

use App\Models\RestaurantHours;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RestaurantHours>
 */
class RestaurantHoursFactory extends Factory
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
        'day_of_week' => fake()->numberBetween(0,6),
        'open_time' => fake()->time(),
        'close_time' => fake()->time(),
        'is_closed' => fake()->boolean(),
        ];
    }
}
