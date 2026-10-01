<?php

namespace Database\Factories;

use App\Models\CustomerProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerProfile>
 */
class CustomerProfilefactoryFactory extends Factory
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
           'phone' => fake()->phonenumber(),
           'date_of_birth' fake()->dob(),
        ];
    }
}
