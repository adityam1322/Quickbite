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
        $categories = [
            'Starters',
            'Main Course',
            'Biryani',
            'North Indian',
            'South Indian',
            'Chinese',
            'Indo-Chinese',
            'Pizza',
            'Burgers',
            'Rolls',
            'Street Food',
            'Maharashtrian Specials',
            'Tandoor',
            'Rice & Noodles',
            'Breads',
            'Thali',
            'Desserts',
            'Beverages',
            'Breakfast',
            'Combos',
        ];

        $name = fake()->randomElement($categories);

        return [
            'restaurant_id' => Restaurant::factory(),

            'name' => $name,

            'slug' => Str::slug($name),

            'description' => fake()->randomElement([
                "Delicious {$name} prepared fresh for our customers.",
                "Freshly prepared {$name} with authentic Indian flavours.",
                "A selection of popular {$name} made with quality ingredients.",
                "Enjoy our freshly prepared {$name}.",
            ]),

            'is_active' => true,
        ];
    }
}