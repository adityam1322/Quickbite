<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainModelSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_builds_a_related_quickbite_demo(): void
    {
        $this->seed();

        $customer = User::query()
            ->where('email', 'customer@quickbite.test')
            ->with(['customerProfile', 'addresses'])
            ->firstOrFail();
        $order = Order::query()
            ->where('order_number', 'QB-DEMO-0001')
            ->with(['restaurant', 'address', 'statusHistories', 'payments.attempts', 'deliveryAssignments'])
            ->firstOrFail();
        $cart = Cart::query()->where('order_id', $order->id)->with('items')->firstOrFail();

        $this->assertNotNull($customer->customerProfile);
        $this->assertSame($customer->id, $order->user_id);
        $this->assertSame($customer->id, $order->address->user_id);
        $this->assertSame($order->restaurant_id, $cart->restaurant_id);
        $this->assertSame(1, $cart->items->count());
        $this->assertSame(1, $order->payments->first()->attempts->count());
        $this->assertDatabaseHas('cuisines', ['name' => 'American']);
        $this->assertDatabaseHas('ratings', ['order_id' => $order->id, 'rating' => 5]);
        $this->assertGreaterThan(0, Restaurant::count());
        $this->assertSame($order->restaurant_id, $cart->items->first()->menuItem->restaurant_id);
    }

    public function test_cart_item_factory_keeps_its_menu_item_in_the_cart_restaurant(): void
    {
        $cart = Cart::factory()->create();
        $item = CartItem::factory()->create(['cart_id' => $cart->id]);

        $this->assertSame($cart->restaurant_id, $item->menuItem->restaurant_id);
        $this->assertSame($item->menu_item_id, $item->menuItemVariant->menu_item_id);
    }
}
