<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\MenuItemAvailabilitie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuItemAvailabilitie>
 */
class MenuItemAvailabilitieFactory extends Factory
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
            'date_of_week' => (string) fake()->numberBetween(0, 6),
            'start_time' => '00:00:00',
            'end_time' => '23:59:59',
            'is_available' => true,
        ];
    }
}
