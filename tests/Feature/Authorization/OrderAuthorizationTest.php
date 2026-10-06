<?php

use App\Models\Order;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('allows a customer to view their own order', function () {
    $customer = User::factory()->create();

    $customer->assignRole('customer');

    $order = Order::factory()->create([
        'user_id' => $customer->id,
    ]);

    Sanctum::actingAs($customer, ['*']);

    $this->getJson("/api/orders/{$order->id}")
        ->assertOk();
});

it('prevents a customer from viewing another customers order', function () {
    $customerA = User::factory()->create();
    $customerB = User::factory()->create();

    $customerA->assignRole('customer');
    $customerB->assignRole('customer');

    $order = Order::factory()->create([
        'user_id' => $customerB->id,
    ]);

    Sanctum::actingAs($customerA, ['*']);

    $this->getJson("/api/orders/{$order->id}")
        ->assertForbidden();
});

it('allows a customer to create orders', function () {
    $customer = User::factory()->create();

    $customer->assignRole('customer');

    expect(
        $customer->can('orders.create')
    )->toBeTrue();
});