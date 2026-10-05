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
        return [
        'user_id' => User::factory(),
        'label' => fake()->word()  ,
        'address_line_1' => fake()->streetAddress(),
        'address_line_2'=> fake()->Address(),
        'city' => fake()->city(),
        'state' => fake()->state(),
        'postal_code' => fake()->numerify('###-###'),
        'latitude' => fake()->latitude(8 , 37),
        'longitude' => fake()->longitude(68 , 98),
        'is_default' => fake()->boolean(40),
        ];
    }
}
