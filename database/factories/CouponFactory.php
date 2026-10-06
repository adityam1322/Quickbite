<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Coupon> */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => Str::upper(fake()->unique()->lexify('??????')),
            'discount_type' => 'fixed',
            'discount_value' => fake()->randomFloat(2, 1, 20),
            'min_order_amount' => fake()->randomFloat(2, 10, 50),
            'max_discount' => fake()->randomFloat(2, 5, 20),
            'start_at' => now()->subDay(),
            'expire_at' => now()->addMonth(),
            'usages_limit' => 100,
            'is_active' => true,
        ];
    }
}
