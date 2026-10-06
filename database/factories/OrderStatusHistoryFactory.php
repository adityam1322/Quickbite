<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<OrderStatusHistory> */
class OrderStatusHistoryFactory extends Factory
{
    protected $model = OrderStatusHistory::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
            'changed_at' => now(),
        ];
    }
}
