<?php

namespace Database\Factories;

use App\Models\Adress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Adress>
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
        'label' => fake()->randomLable()  ,
        'address_line_1' => fake()->streetAddress(),
        'address_line_2'=> fake()->secondStreetAddress(),
        'city' => fake()->city(),
        'state' => fake()->state(),
        'postal_code' => fake()->postal_code(),
        'latitude'fake()->longitude(8 , 37),
        'longitude' => fake()->longitude(68 , 98),
        'is_default' => fake()->boolean(40),
        ];
    }
}
