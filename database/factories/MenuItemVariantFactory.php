<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\MenuItemVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuItemVariant>
 */
class MenuItemVariantFactory extends Factory
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
            'name' => fake()->randomElement(['Small', 'Regular', 'Large', 'Extra cheese']),
            'price' => fake()->randomFloat(2, 0, 12),
            'is_avialable' => true,
        ];
    }
}
