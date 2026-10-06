<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\MenuItemImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuItemImage>
 */
class MenuItemImageFactory extends Factory
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
            'image_url' => fake()->imageUrl(),
            'is_primary' => true,
        ];
    }
}
