<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Rating> */
class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),

            'user_id' => fn(array $attributes): int =>
            Order::findOrFail($attributes['order_id'])->user_id,

            'restaurant_id' => fn(array $attributes): int =>
            Order::findOrFail($attributes['order_id'])->restaurant_id,

            'rating' => fake()->numberBetween(3, 5),

            'review' => fake()->optional(0.8)->randomElement([
                'Food was delicious and delivered on time.',
                'Really tasty food and good portion size.',
                'Great taste and fresh ingredients.',
                'The food was hot and well packed.',
                'Good quality food and quick delivery.',
                'Loved the taste. Will order again.',
                'Everything was fresh and properly packed.',
                'Good food and excellent overall experience.',
                'The order arrived on time and tasted great.',
                'Nice food quality and reasonable pricing.',
            ]),
        ];
    }
}
