<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locations = [
            [
                'area' => 'Dadar East',
                'road' => 'Dr Babasaheb Ambedkar Road',
                'pincode' => '400014',
                'latitude' => 19.0178,
                'longitude' => 73.0006,
            ],
            [
                'area' => 'Matunga East',
                'road' => 'Lal Bahadur Shastri Road',
                'pincode' => '400019',
                'latitude' => 19.0269,
                'longitude' => 72.8553,
            ],
            [
                'area' => 'Sion',
                'road' => 'Sion Main Road',
                'pincode' => '400022',
                'latitude' => 19.0469,
                'longitude' => 72.8633,
            ],
            [
                'area' => 'Kurla East',
                'road' => 'LBS Marg',
                'pincode' => '400070',
                'latitude' => 19.0654,
                'longitude' => 72.8792,
            ],
            [
                'area' => 'Ghatkopar East',
                'road' => 'MG Road',
                'pincode' => '400077',
                'latitude' => 19.0860,
                'longitude' => 72.9081,
            ],
            [
                'area' => 'Vikhroli East',
                'road' => 'LBS Marg',
                'pincode' => '400083',
                'latitude' => 19.1111,
                'longitude' => 72.9278,
            ],
            [
                'area' => 'Bhandup East',
                'road' => 'Station Road',
                'pincode' => '400042',
                'latitude' => 19.1450,
                'longitude' => 72.9370,
            ],
            [
                'area' => 'Mulund East',
                'road' => 'RHB Road',
                'pincode' => '400081',
                'latitude' => 19.1726,
                'longitude' => 72.9567,
            ],
            [
                'area' => 'Byculla East',
                'road' => 'Sane Guruji Marg',
                'pincode' => '400027',
                'latitude' => 18.9766,
                'longitude' => 72.8331,
            ],
            [
                'area' => 'Parel',
                'road' => 'Dr S S Rao Road',
                'pincode' => '400012',
                'latitude' => 19.0006,
                'longitude' => 72.8376,
            ],
        ];

        $location = fake()->randomElement($locations);

        return [
            'user_id' => User::factory(),

            'label' => fake()->randomElement([
                'Home',
                'Work',
            ]),

            'address_line_1' => fake()->numberBetween(1, 999)
                . ', '
                . fake()->randomElement([
                    'Shivaji Nagar',
                    'Ganesh Nagar',
                    'Gandhi Nagar',
                    'Ram Nagar',
                    'Krishna Nagar',
                    'Sai Nagar',
                    'Shanti Nagar',
                ])
                . ', '
                . $location['road'],

            'address_line_2' => fake()->optional(0.7)->randomElement([
                'Near Railway Station',
                'Near Central Railway Station',
                'Opposite Main Market',
                'Near Bus Depot',
                'Near Municipal School',
                'Behind Main Road',
                'Near Post Office',
                'Near Shopping Complex',
            ]),

            'city' => 'Mumbai',

            'state' => 'Maharashtra',

            'postal_code' => $location['pincode'],

            'latitude' => $location['latitude'],

            'longitude' => $location['longitude'],

            'is_default' => true,
        ];
    }
}