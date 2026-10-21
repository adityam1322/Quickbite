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
    protected $model = Order::class;

    public function definition(): array
    {
        $orderStatus = fake()->randomElement([
            'pending',
            'confirmed',
            'preparing',
            'ready',
            'delivered',
            'cancelled',
        ]);

        $paymentStatus = match ($orderStatus) {
            'pending' => fake()->randomElement([
                'pending',
                'paid',
            ]),
            'cancelled' => fake()->randomElement([
                'failed',
                'refunded',
            ]),
            'delivered' => 'paid',
            default => 'paid',
        };

        $subtotal = fake()->randomElement([
            199,
            249,
            299,
            349,
            399,
            499,
            599,
            699,
            799,
        ]);

        $taxAmount = round($subtotal * 0.05, 2);

        $deliveryFee = fake()->randomElement([
            20,
            25,
            30,
            35,
            40,
            50,
        ]);

        $discountAmount = fake()->randomElement([
            0,
            20,
            30,
            40,
            50,
            75,
            100,
        ]);

        $totalAmount = max(
            0,
            $subtotal + $taxAmount + $deliveryFee - $discountAmount
        );

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
            'payment_status' => $paymentStatus,
            'idempotency_key' => 'seed-' . fake()->unique()->bothify('########-??????'),
            'order_status' => $orderStatus,
            'notes' => fake()->randomElement([
                'Please deliver at the main gate.',
                'Please call when you arrive.',
                'Please do not ring the bell.',
                'Leave the order with the security guard.',
                'Please deliver near the reception.',
                'Call me before delivery.',
            ]),
        ];
    }
}
