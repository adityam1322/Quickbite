<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AddressSeeder::class,
            CartSeeder::class,
            CartItemSeeder::class,
            CouponSeeder::class,
            CuisineSeeder::class,
            CustomerProfileSeeder::class,
            DeliveryPartnerProfileSeeder::class,
            RestaurantHoursSeeder::class,
            RestaurantSeeder::class,
            RestaurantServiceAreaSeeder::class,
            RestaurantStaffSeeder::class,
            RatingSeeder::class,
            RefundSeeder::class,
            MenuCategorieSeeder::class,
            MenuItemSeeder::class,
            MenuItemVariantSeeder::class,
            MenuItemPriceSeeder::class,
            MenuItemAvailabilitieSeeder::class,
            MenuItemImageSeeder::class,
            OrderSeeder::class,
            OrderStatusHistorySeeder::class,
            PaymentAttemptSeeder::class,
            PaymentSeeder::class,
            RolesAndPermissionsSeeder::class,
            
        ]);
    }
}
