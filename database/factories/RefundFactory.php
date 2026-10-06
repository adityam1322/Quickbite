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
            'refund_refrence' => fake()->uuid(),
            'amount' => fake()->randomFloat(2, 1, 50),
            'reson' => fake()->sentence(),
            'status' => 'pending',
            'refunded' => null,
        ];
    }
}
