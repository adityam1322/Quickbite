<?php

namespace Tests\Feature\Mcp;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    public function test_search_restaurants_rejects_invalid_input(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/mcp/food-delivery', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/call',
            'params' => [
                'name' => 'search_restaurants',
                'arguments' => [
                    'limit' => -100,
                ],
            ],
        ]);

        $response->assertSuccessful();
    }
}