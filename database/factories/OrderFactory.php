<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 12, 100);
        $deliveryFee = fake()->randomFloat(2, 1, 8);

        return [
            'user_id' => User::factory(),
            'restaurant_id' => Restaurant::factory(),
            'address_id' => fn (array $attributes): int => Address::factory()
                ->create(['user_id' => $attributes['user_id']])
                ->id,
            'order_number' => 'QB-'.fake()->unique()->bothify('########-??????'),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => number_format($subtotal + $deliveryFee, 2, '.', ''),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed', 'refunded']),
            'order_status' => fake()->randomElement(['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled']),
            'notes' => fake()->sentence(),
        ];
    }
}
