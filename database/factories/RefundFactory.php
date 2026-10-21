<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Refund> */
class RefundFactory extends Factory
{
    protected $model = Refund::class;

    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'refund_refrence' => 'REF-' . fake()->unique()->bothify('########-??????'),
            'amount' => fake()->randomElement([
                99,
                149,
                199,
                249,
                299,
                349,
                399,
                499,
            ]),
            'reson' => fake()->randomElement([
                'Customer cancelled the order.',
                'Order was cancelled by the restaurant.',
                'Payment was charged but order could not be processed.',
                'Customer requested a refund.',
                'Order was not delivered.',
                'Duplicate payment was detected.',
                'Restaurant was unable to fulfill the order.',
            ]),
            'status' => 'pending',
            'refunded' => null,
        ];
    }
}
