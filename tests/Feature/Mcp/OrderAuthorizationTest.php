<?php

namespace Tests\Feature\Mcp;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderAuthorizationTest extends TestCase
{
    public function test_customer_cannot_access_another_customers_order(): void
    {
        $customerA = User::factory()->create();
        $customerB = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $customerA->id,
            'restaurant_id' => $restaurant->id,
        ]);

        Sanctum::actingAs($customerB);

        $response = $this->postJson('/mcp/food-delivery', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/call',
            'params' => [
                'name' => 'get_order_status',
                'arguments' => [
                    'order_id' => $order->id,
                ],
            ],
        ]);

        $response->assertOk();

        $toolResult = json_decode(
            $response->json('result.content.0.text'),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        $this->assertFalse($toolResult['success']);
        $this->assertSame('Order not found.', $toolResult['message']);
    }

    public function test_customer_can_access_their_own_order(): void
    {
        $customer = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'restaurant_id' => $restaurant->id,
        ]);

        Sanctum::actingAs($customer);

        $response = $this->postJson('/mcp/food-delivery', [
            'jsonrpc' => '2.0',
            'id' => 2,
            'method' => 'tools/call',
            'params' => [
                'name' => 'get_order_status',
                'arguments' => [
                    'order_id' => $order->id,
                ],
            ],
        ]);

        $response->assertOk();

        $toolResult = json_decode(
            $response->json('result.content.0.text'),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        $this->assertTrue($toolResult['success']);
        $this->assertSame($order->id, $toolResult['data']['order_id']);
    }
}
