<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
            'restaurant_id' => fn (array $attributes): int => Order::findOrFail($attributes['order_id'])->restaurant_id,
            'status' => fake()->randomElement(['active', 'checked_out', 'abandoned']),
        ];
    }
}
