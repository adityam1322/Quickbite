<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuCategorie;

class MenuCategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    MenuCategorie::factory()->count(10)->create();
}
}
