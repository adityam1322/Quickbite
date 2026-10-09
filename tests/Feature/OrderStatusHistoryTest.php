<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Services\OrderStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_change_is_saved_in_history(): void
    {
        $order = Order::factory()->create([
            'order_status' => 'pending',
        ]);

        app(OrderStatusService::class)->changeStatus(
            $order->id,
            'confirmed'
        );

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('orders_status_histories', [
            'order_id' => $order->id,
            'status' => 'confirmed',
        ]);
    }
}