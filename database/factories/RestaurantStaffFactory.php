<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\RestaurantStaff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RestaurantStaff>
 */
class RestaurantStaffFactory extends Factory
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
            'user_id' => User::factory(),
            'role' => fake()->randomElement(['manager', 'chef', 'cashier']),
            'is_active' => true,
        ];
    }
}
