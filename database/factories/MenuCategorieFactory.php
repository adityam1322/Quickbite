<?php

namespace Database\Factories;

use App\Models\MenuCategorie;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MenuCategorie>
 */
class MenuCategorieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement(['Starters', 'Mains', 'Desserts', 'Drinks']);

        return [
            'restaurant_id' => Restaurant::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
