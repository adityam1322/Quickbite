<?php

namespace Database\Factories;

use App\Models\DeliveryPartnerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeliveryPartnerProfile>
 */
class DeliveryPartnerProfileFactory extends Factory
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
        'vehicle_type' => fake()->randomElement(['Bike','Bicycle','Scooter']),
        'vehicle_number' => fake()->unique()->bothify('MH-##-??-####'),
        'license_number' => fake()->unique()->bothify('DL-########'),
        'is_available' => fake()->boolean(),
        ];
    }
}
