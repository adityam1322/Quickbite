<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Restaurant>
 */
class RestaurantFactory extends Factory
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
                'area' => 'Dadar East',
                'address' => 'Dr Babasaheb Ambedkar Road',
                'pincode' => '400014',
            ],
            [
                'area' => 'Matunga East',
                'address' => 'Lal Bahadur Shastri Market Road',
                'pincode' => '400019',
            ],
            [
                'area' => 'Sion',
                'address' => 'Sion Main Road',
                'pincode' => '400022',
            ],
            [
                'area' => 'Kurla East',
                'address' => 'LBS Marg',
                'pincode' => '400070',
            ],
            [
                'area' => 'Ghatkopar East',
                'address' => 'MG Road',
                'pincode' => '400077',
            ],
            [
                'area' => 'Vikhroli East',
                'address' => 'LBS Marg',
                'pincode' => '400083',
            ],
            [
                'area' => 'Bhandup East',
                'address' => 'Station Road',
                'pincode' => '400078',
            ],
            [
                'area' => 'Mulund East',
                'address' => 'RHB Road',
                'pincode' => '400081',
            ],
            [
                'area' => 'Byculla East',
                'address' => 'Sane Guruji Marg',
                'pincode' => '400027',
            ],
            [
                'area' => 'Parel',
                'address' => 'Dr S S Rao Road',
                'pincode' => '400012',
            ],
        ];

        $location = fake()->randomElement($areas);

        $restaurantPrefixes = [
            'Mumbai',
            'Bombay',
            'Maharashtra',
            'Central Mumbai',
            'Desi',
            'Apna',
            'Royal',
            'Shree',
            'Urban',
            'Spice',
        ];

        $restaurantTypes = [
            'Spice',
            'Kitchen',
            'Bites',
            'Tadka',
            'Zaika',
            'Restaurant',
            'Cafe',
            'Tandoor',
            'Food Hub',
            'Family Restaurant',
        ];

        $name = fake()->randomElement($restaurantPrefixes)
            . ' '
            . fake()->randomElement($restaurantTypes)
            . ' '
            .fake()->numberBetween(1000, 999999);

        return [
            'name' => $name,

            'slug' => fake()->unique()->slug(),

            'description' => fake()->randomElement([
                'Authentic Indian food prepared fresh with quality ingredients.',
                'Delicious Mumbai-style food with traditional flavours.',
                'Freshly prepared meals with a perfect blend of Indian spices.',
                'Serving tasty food inspired by the flavours of Maharashtra.',
                'A local favourite for fresh and delicious Indian cuisine.',
                'Enjoy authentic flavours and freshly prepared dishes.',
            ]),

            'phone' => '+91 9' . fake()->numerify('#########'),

            'email' => fake()->unique()->userName() . '@gmail.com',

            'address_line_1' => fake()->buildingNumber()
                . ', '
                . $location['address'],

            'address_line_2' => fake()->randomElement([
                'Near Railway Station',
                'Near Central Railway Station',
                'Opposite Railway Station',
                'Near Main Market',
                'Near Bus Depot',
                'Near Station Road',
                'Opposite Main Road',
            ]),

            'city' => 'Mumbai',

            'state' => 'Maharashtra',

            'postal_code' => $location['pincode'],

            'latitude' => fake()->randomFloat(
                6,
                18.95,
                19.25
            ),

            'longitude' => fake()->randomFloat(
                6,
                72.82,
                73.00
            ),

            'is_active' => true,
        ];
    }
}