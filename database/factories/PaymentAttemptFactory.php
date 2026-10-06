<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\PaymentAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PaymentAttempt> */
class PaymentAttemptFactory extends Factory
{
    protected $model = PaymentAttempt::class;

    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'attempt_number' => 1,
            'transaction_id' => fake()->uuid(),
            'amount' => fake()->randomFloat(2, 10, 100),
            'status' => 'succeeded',
            'responce_massage' => 'Payment authorized.',
            'attempted-at' => now(),
        ];
    }
}
