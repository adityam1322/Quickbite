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

            'name' => fake()->randomElement([
                'Small',
                'Regular',
                'Large',
                'Extra Cheese',
                'Extra Paneer',
                'Extra Chicken',
                'Family Pack',
            ]),

            'price' => fake()->randomElement([
                0,
                20,
                30,
                40,
                50,
                60,
                80,
                100,
                120,
                150,
                180,
                200,
            ]),

            'is_avialable' => true,
        ];
    }
}
