<?php

namespace Database\Factories;

use App\Models\DeliveryAssignment;
use App\Models\DeliveryPartnerProfile;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeliveryAssignment>
 */
class DeliveryAssignmentFactory extends Factory
{
    protected $model = DeliveryAssignment::class;

    public function definition(): array
    {
        $status = fake()->randomElement([
            'assigned',
            'picked_up',
            'delivered',
        ]);

        $assignedAt = fake()->dateTimeBetween(
            '-2 days',
            'now'
        );

        return [
            'order_id' => Order::factory(),
            'delivery_partner_profile_id' => DeliveryPartnerProfile::factory(),
            'assigned_at' => $assignedAt,
            'picked_up_at' => in_array($status, [
                'picked_up',
                'delivered',
            ])
                ? fake()->dateTimeBetween(
                    $assignedAt,
                    'now'
                )
                : null,

            'status' => $status,
        ];
    }
}