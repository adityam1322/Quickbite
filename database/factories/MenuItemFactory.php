<?php

namespace Database\Factories;

use App\Models\MenuCategorie;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'menu_categories_id' => MenuCategorie::factory(),
            'restaurant_id' => fn (array $attributes): int => MenuCategorie::findOrFail($attributes['menu_categories_id'])->restaurant_id,
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'discription' => fake()->sentence(),
            'is_vegetarian' => fake()->boolean(40),
            'is_available' => true,
        ];
    }
}
