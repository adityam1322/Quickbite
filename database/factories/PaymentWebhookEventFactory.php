<?php

namespace Database\Factories;

use App\Models\PaymentWebhookEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentWebhookEventFactory extends Factory
{
    protected $model = PaymentWebhookEvent::class;

    public function definition(): array
    {
        return [
            'event_id' => fake()->unique()->uuid(),
            'event_type' => 'payment.succeeded',
            'status' => 'processed',
            'processed_at' => now(),
        ];
    }
}