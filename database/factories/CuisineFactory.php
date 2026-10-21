<?php

namespace Database\Factories;

use App\Models\Cuisine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cuisine>
 */
class CuisineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'North Indian',
            'South Indian',
            'Maharashtrian',
            'Punjabi',
            'Gujarati',
            'Bengali',
            'Rajasthani',
            'Mughlai',
            'Chinese',
            'Indo-Chinese',
            'Italian',
            'Japanese',
            'Mexican',
            'Thai',
            'Korean',
            'Continental',
            'Fast Food',
            'Street Food',
            'Biryani',
            'Pizza',
            'Burger',
            'Desserts',
            'Cafe',
            'Healthy Food',
            'Seafood',
        ]);

        return [
            'name' => $name,

            'slug' => str($name)
                ->slug()
                ->toString(),

            'is_active' => true,
        ];
    }
}