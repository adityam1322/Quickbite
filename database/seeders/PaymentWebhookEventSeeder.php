<?php

namespace Database\Seeders;

use App\Models\PaymentWebhookEvent;
use Illuminate\Database\Seeder;

class PaymentWebhookEventSeeder extends Seeder
{
    public function run(): void
    {
        PaymentWebhookEvent::factory()->count(5)->create();
    }
}