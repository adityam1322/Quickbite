<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

it('rejects unauthenticated users', function () {
    $this->getJson('/api/me')
        ->assertUnauthorized();
});

it('allows an authenticated user to access their profile', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $user->assignRole('customer');

    Sanctum::actingAs($user, ['*']);

    $this->getJson('/api/me')
        ->assertOk()
        ->assertJsonPath('user.id', $user->id);
});

it('allows login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
    ]);

    $user->assignRole('customer');

    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password123',
    ])
        ->assertOk()
        ->assertJsonStructure([
            'message',
            'token',
            'user',
        ]);
});

it('rejects invalid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
    ]);

    $user->assignRole('customer');

    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])
        ->assertUnprocessable();
});

it('prevents an unverified user from logging in', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
        'email_verified_at' => null,
    ]);

    $user->assignRole('customer');

    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password123',
    ])
        ->assertForbidden();
});

it('revokes the current token on logout', function () {
    $user = User::factory()->create();

    $user->assignRole('customer');

    $createdToken = $user->createToken('test-token');

    $token = $createdToken->plainTextToken;
    $tokenId = $createdToken->accessToken->id;

    $this->withToken($token)
        ->postJson('/api/logout')
        ->assertOk();

    // Confirm the current token was deleted.
    $this->assertDatabaseMissing('personal_access_tokens', [
        'id' => $tokenId,
    ]);

    // Clear cached authentication guards before checking the old token.
    $this->app['auth']->forgetGuards();

    $this->withToken($token)
        ->getJson('/api/me')
        ->assertUnauthorized();
});

it('revokes all tokens on logout all', function () {
    $user = User::factory()->create();

    $user->assignRole('customer');

    $createdToken1 = $user->createToken('device-one');
    $createdToken2 = $user->createToken('device-two');

    $token1 = $createdToken1->plainTextToken;
    $token2 = $createdToken2->plainTextToken;

    $tokenId1 = $createdToken1->accessToken->id;
    $tokenId2 = $createdToken2->accessToken->id;

    $this->withToken($token1)
        ->postJson('/api/logout-all')
        ->assertOk();

    // Confirm both tokens were deleted.
    $this->assertDatabaseMissing('personal_access_tokens', [
        'id' => $tokenId1,
    ]);

    $this->assertDatabaseMissing('personal_access_tokens', [
        'id' => $tokenId2,
    ]);

    $this->app['auth']->forgetGuards();

    $this->withToken($token1)
        ->getJson('/api/me')
        ->assertUnauthorized();

    $this->app['auth']->forgetGuards();

    $this->withToken($token2)
        ->getJson('/api/me')
        ->assertUnauthorized();
});