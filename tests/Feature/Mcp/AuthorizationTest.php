<?php

namespace Tests\Feature\Mcp;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    public function test_mcp_requires_authentication(): void
    {
        $response = $this->postJson('/mcp/food-delivery', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/list',
            'params' => [],
        ]);

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_mcp(): void
    {
        $user = User::query()->create([
            'name' => 'MCP Test User',
            'email' => 'mcp-test@example.com',
            'password' => Hash::make('password'),
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/mcp/food-delivery', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/list',
            'params' => [],
        ]);

        $response->assertSuccessful();
    }
}