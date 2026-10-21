<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\RestaurantServiceArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RestaurantServiceArea>
 */
class RestaurantServiceAreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $areas = [
            [
                'name' => 'Dadar East',
                'postal_code' => '400014',
            ],
            [
                'name' => 'Matunga East',
                'postal_code' => '400019',
            ],
            [
                'name' => 'Sion',
                'postal_code' => '400022',
            ],
            [
                'name' => 'Kurla East',
                'postal_code' => '400070',
            ],
            [
                'name' => 'Ghatkopar East',
                'postal_code' => '400077',
            ],
            [
                'name' => 'Vikhroli East',
                'postal_code' => '400083',
            ],
            [
                'name' => 'Bhandup East',
                'postal_code' => '400042',
            ],
            [
                'name' => 'Mulund East',
                'postal_code' => '400081',
            ],
            [
                'name' => 'Byculla East',
                'postal_code' => '400027',
            ],
            [
                'name' => 'Parel',
                'postal_code' => '400012',
            ],
        ];

        $area = fake()->randomElement($areas);

        return [
            'restaurant_id' => Restaurant::factory(),
            'area_name' => $area['name'],
            'city' => 'Mumbai',
            'postal_code' => $area['postal_code'],
            'is_active' => true,
        ];
    }
}
