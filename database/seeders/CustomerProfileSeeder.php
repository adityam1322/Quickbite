<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerProfile;
use App\Models\User;

class CustomerProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        foreach($users as $user){
            CustomerProfile::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
