<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerProfile extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerProfileSeeder::factory()->count(10)->created();
    }
}
