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
            'user_id' => fn (array $attributes): int => Order::findOrFail($attributes['order_id'])->user_id,
            'restaurant_id' => fn (array $attributes): int => Order::findOrFail($attributes['order_id'])->restaurant_id,
            'rating' => fake()->numberBetween(1, 5),
            'review' => fake()->optional()->sentence(),
        ];
    }
}
