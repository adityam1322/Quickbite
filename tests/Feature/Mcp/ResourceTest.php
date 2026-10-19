<?php

namespace Tests\Feature\Mcp;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    public function test_authenticated_user_can_discover_mcp_resources(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/mcp/food-delivery', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'resources/list',
            'params' => [],
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'jsonrpc',
            'id',
            'result',
        ]);
    }

    public function test_authenticated_user_can_read_mcp_resource(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        // Replace this URI with the exact URI
        // registered by your RestaurantResource.
        $response = $this->postJson('/mcp/food-delivery', [
            'jsonrpc' => '2.0',
            'id' => 2,
            'method' => 'resources/read',
            'params' => [
                'uri' => 'restaurant://1',
            ],
        ]);

        $this->assertNotEquals(500, $response->status());
    }
}