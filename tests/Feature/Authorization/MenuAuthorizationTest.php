<?php

use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('allows staff to update their own restaurant menu item', function () {
    $staff = User::factory()->create();
    $staff->assignRole('restaurant_staff');

    $restaurant = Restaurant::factory()->create();

    \App\Models\RestaurantStaff::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $staff->id,
    ]);

    $menuItem = MenuItem::factory()->create([
        'restaurant_id' => $restaurant->id,
    ]);

    Sanctum::actingAs($staff, ['*']);

    $this->putJson(
        "/api/menu-items/{$menuItem->id}",
        [
            'name' => 'Updated Item',
        ]
    )->assertOk();
});