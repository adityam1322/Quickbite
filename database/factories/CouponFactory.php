<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        $discountType = fake()->randomElement([
            'fixed',
            'percentage',
        ]);

        $couponNames = [
            'WELCOME',
            'MUMBAI',
            'QUICKBITE',
            'FIRSTORDER',
            'FOODIE',
            'YUMMY',
            'DINNER',
            'LUNCH',
            'WEEKEND',
            'FREEDOM',
        ];

        $prefix = fake()->randomElement($couponNames);

        return [
            /*
             * Example:
             * WELCOME25
             * MUMBAI50
             * QUICKBITE20
             */
            'code' => Str::upper(
                $prefix . fake()->numberBetween(10, 99)
            ),

            /*
             * fixed      = ₹ amount off
             * percentage = percentage off
             */
            'discount_type' => $discountType,

            /*
             * Fixed:
             * ₹20 - ₹150
             *
             * Percentage:
             * 10% - 40%
             */
            'discount_value' => $discountType === 'fixed'
                ? fake()->randomFloat(2, 20, 150)
                : fake()->randomFloat(2, 10, 40),

            /*
             * Minimum order value in ₹
             */
            'min_order_amount' => fake()->randomElement([
                199,
                249,
                299,
                349,
                399,
                499,
            ]),

            /*
             * Maximum discount in ₹
             */
            'max_discount' => $discountType === 'percentage'
                ? fake()->randomElement([
                    50,
                    75,
                    100,
                    125,
                    150,
                    200,
                ])
                : null,

            /*
             * Coupon starts from yesterday
             */
            'start_at' => now()->subDay(),

            /*
             * Coupon valid for the next 30 days
             */
            'expire_at' => now()->addDays(30),

            /*
             * Maximum number of times this coupon
             * can be used.
             */
            'usages_limit' => fake()->randomElement([
                50,
                100,
                250,
                500,
                1000,
            ]),

            'is_active' => true,
        ];
    }
}