<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\MenuItemPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuItemPrice>
 */
class MenuItemPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_item_id' => MenuItem::factory(),
            'price' => fake()->randomFloat(2, 4, 45),
            'currency' => 'USD',
            'effective_from' => today()->subDay(),
            'effective_until' => null,
        ];
    }
}
