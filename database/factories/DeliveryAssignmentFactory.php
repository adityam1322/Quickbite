<?php

namespace Database\Factories;

use App\Models\DeliveryAssignment;
use App\Models\DeliveryPartnerProfile;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<DeliveryAssignment> */
class DeliveryAssignmentFactory extends Factory
{
    protected $model = DeliveryAssignment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'delivery_partner_profile_id' => DeliveryPartnerProfile::factory(),
            'assigned_at' => now(),
            'picked_up_at' => null,
            'status' => 'assigned',
        ];
    }
}
