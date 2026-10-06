<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\RestaurantHours;
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
            'day_of_week' => fake()->numberBetween(0, 6),
            'open_time' => '09:00:00',
            'close_time' => '21:00:00',
            'is_closed' => false,
        ];
    }
}
