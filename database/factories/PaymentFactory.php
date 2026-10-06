<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Payment> */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'transaction_id' => fake()->uuid(),
            'payment_method' => fake()->randomElement(['cash_on_delivery', 'card']),
            'amount' => fake()->randomFloat(2, 10, 100),
            'currency' => 'USD',
            'status' => 'pending',
            'paid_at' => now(),
        ];
    }
}
