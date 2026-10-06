<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\MenuCategorie;
use App\Models\MenuItem;
use App\Models\MenuItemVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 4);
        $unitPrice = fake()->randomFloat(2, 4, 45);

        return [
            'cart_id' => Cart::factory(),
            'menu_item_id' => function (array $attributes): int {
                $cart = Cart::findOrFail($attributes['cart_id']);
                $category = MenuCategorie::factory()->create([
                    'restaurant_id' => $cart->restaurant_id,
                ]);

                return MenuItem::factory()->create([
                    'restaurant_id' => $cart->restaurant_id,
                    'menu_categories_id' => $category->id,
                ])->id;
            },
            'menu_items_variants_id' => fn (array $attributes): int => MenuItemVariant::factory()
                ->create(['menu_item_id' => $attributes['menu_item_id']])
                ->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => number_format($unitPrice * $quantity, 2, '.', ''),
        ];
    }
}
