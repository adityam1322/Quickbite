<?php

use App\Models\Restaurant;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('allows restaurant staff to update their own restaurant', function () {
    $staff = User::factory()->create();

    $staff->assignRole('restaurant_staff');

    $restaurant = Restaurant::factory()->create();

    \App\Models\RestaurantStaff::factory()->create([
    'restaurant_id' => $restaurant->id,
    'user_id' => $staff->id,
]);

    Sanctum::actingAs($staff, ['*']);

    $this->putJson(
        "/api/restaurants/{$restaurant->id}",
        [
            'name' => 'Updated Restaurant',
        ]
    )
        ->assertOk();
});

it('prevents restaurant staff from updating another restaurant', function () {
    $staff = User::factory()->create();

    $staff->assignRole('restaurant_staff');

    $restaurant = Restaurant::factory()->create();

    Sanctum::actingAs($staff, ['*']);

    $this->putJson(
        "/api/restaurants/{$restaurant->id}",
        [
            'name' => 'Unauthorized Restaurant',
        ]
    )
        ->assertForbidden();
});

it('allows an administrator to update any restaurant', function () {
    $admin = User::factory()->create();

    $admin->assignRole('administrator');

    $restaurant = Restaurant::factory()->create();

    Sanctum::actingAs($admin, ['*']);

    $this->putJson(
        "/api/restaurants/{$restaurant->id}",
        [
            'name' => 'Admin Restaurant',
        ]
    )
        ->assertOk();
});