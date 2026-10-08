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
        $taxAmount = $subtotal * 0.05;
        $deliveryFee = fake()->randomFloat(2, 1, 8);
        $discountAmount = fake()->randomFloat(2, 0, 10);
        $totalAmount =
            $subtotal
            + $taxAmount
            + $deliveryFee
            - $discountAmount;

        return [
            'user_id' => User::factory(),
            'restaurant_id' => Restaurant::factory(),
            'address_id' => fn(array $attributes): int => Address::factory()
                ->create([
                    'user_id' => $attributes['user_id'],
                ])
                ->id,
            'order_number' => 'QB-' . fake()->unique()->bothify('########-??????'),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'delivery_fee' => $deliveryFee,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'payment_status' => fake()->randomElement([
                'pending',
                'paid',
                'failed',
                'refunded',
            ]),

             'idempotency_key' => 'seed-' . fake()->unique()->bothify('########-??????'),


            'order_status' => fake()->randomElement([
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'delivered',
                'cancelled',
            ]),

            'notes' => fake()->sentence(),
        ];
    }
}
