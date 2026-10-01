<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryPartnerProfile extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryPartnerProfile::factory()->count(10)->created();
    }
}
