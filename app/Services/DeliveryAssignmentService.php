<?php

namespace App\Services;

use App\Models\DeliveryAssignment;
use App\Models\DeliveryPartnerProfile;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DeliveryAssignmentService
{
    public function assign(Order $order): ?DeliveryAssignment
    {
        return DB::transaction(function () use ($order) {

            $order->loadMissing('address');

            // 1. Only ready orders can be assigned
            if ($order->order_status !== 'ready_for_pickup') {
                return null;
            }

            // 2. Don't assign the same order twice
            $alreadyAssigned = DeliveryAssignment::query()
                ->where('order_id', $order->id)
                ->exists();

            if ($alreadyAssigned) {
                return null;
            }

            // 3. Get order area
            $orderArea = $order->address?->city;

            if (! $orderArea) {
                return null;
            }

            // 4. Find the best delivery partner
            $deliveryPartner = DeliveryPartnerProfile::query()
                ->where('is_available', true)
                ->where('area', $orderArea)
                ->withCount([
                    'deliveryAssignments as active_workload' => function ($query) {
                        $query->whereIn('status', [
                            'assigned',
                            'picked_up',
                        ]);
                    },
                ])
                ->orderBy('active_workload')
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            // 5. No eligible partner found
            if (! $deliveryPartner) {
                return null;
            }

            // 6. Create assignment
            return DeliveryAssignment::create([
                'order_id' => $order->id,
                'delivery_partner_profile_id' => $deliveryPartner->id,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);
        });
    }
}