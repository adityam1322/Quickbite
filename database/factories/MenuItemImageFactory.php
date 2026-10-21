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

    'image_url' => fake()->randomElement([
        'https://images.unsplash.com/photo-1601050690597-df0568f70950',
        'https://images.unsplash.com/photo-1563379091339-03246963d96c',
        'https://images.unsplash.com/photo-1565958011703-44f9829ba187',
        'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38',
        'https://images.unsplash.com/photo-1550547660-d9450f859349',
    ]),

    'is_primary' => true,
];
    }
}
