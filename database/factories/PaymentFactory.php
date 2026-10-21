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
    'payment_method' => fake()->randomElement([
        'cash_on_delivery',
        'upi',
        'card',
        'net_banking',
        'wallet',
    ]),
    'amount' => fake()->randomElement([
        199,
        249,
        299,
        349,
        399,
        499,
        599,
        699,
        799,
    ]),
    'currency' => 'INR',
    'status' => fake()->randomElement([
        'pending',
        'successful',
        'failed',
        'refunded',
    ]),
    'paid_at' => fake()->optional(0.8)->dateTimeBetween(
        '-2 days',
        'now'
    ),
];
    }
}
