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
        $name = fake()->unique()->name();

        return [
        'name' => $name,
        'slug' => strtolower(str_replace(' ', '_', $name)),
        'description'=> fake()->paragraph(),
        'phone' => fake()->unique()->phoneNumber(),
        'email' => strtolower(str_replace(' ', '', $name)) . '@gmail.com',
        'address_line_1' => fake()->unique()->streetAddress(),
        'address_line_2'=> fake()->unique()->Address(),
        'city' => fake()->city(),
        'state' => fake()->state(),
        'postal_code' => fake()->unique()->numerify('###-###'),
        'latitude' => fake()->latitude(8 , 37),
        'longitude' => fake()->longitude(68 , 98),
        'is_active' => fake()->boolean(),
        ];
    }
}
