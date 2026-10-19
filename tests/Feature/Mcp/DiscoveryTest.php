<?php

namespace Tests\Feature\Mcp;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DiscoveryTest extends TestCase
{
    public function test_mcp_discovery_returns_server_capabilities(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/mcp/food-delivery', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'initialize',
            'params' => [
                'protocolVersion' => '2025-11-25',
                'capabilities' => [],
                'clientInfo' => [
                    'name' => 'quickbite-test',
                    'version' => '1.0.0',
                ],
            ],
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'jsonrpc',
            'id',
            'result',
        ]);
    }

    public function test_mcp_tools_are_discoverable(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/mcp/food-delivery', [
            'jsonrpc' => '2.0',
            'id' => 2,
            'method' => 'tools/list',
            'params' => [],
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'jsonrpc',
            'id',
            'result' => [
                'tools',
            ],
        ]);

        $response->assertJsonFragment([
            'name' => 'search_restaurants',
        ]);

        $response->assertJsonFragment([
            'name' => 'get_restaurant_menu',
        ]);

        $response->assertJsonFragment([
            'name' => 'get_order_status',
        ]);
    }
}