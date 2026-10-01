<?php

namespace Database\Factories;

use App\Models\DeliveryPartnerProfile;
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
        'vehicle_type' => fake()->vehicleType(),
        'vehicle_number' => fake()->unique()->vehicalNumber('####'),
        'license_number' => fake()->unique()->licenceNumber(),
        'is_available' => fake()->boolean(),
        ];
    }
}
