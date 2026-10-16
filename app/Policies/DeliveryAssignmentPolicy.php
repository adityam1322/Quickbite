<?php

namespace App\Policies;

use App\Models\DeliveryAssignment;
use App\Models\User;

class DeliveryAssignmentPolicy
{
    public function view(
        User $user,
        DeliveryAssignment $deliveryAssignment
    ): bool {
        if ($user->hasRole('administrator')) {
            return true;
        }

        return $deliveryAssignment->deliveryPartner?->user_id === $user->id;
    }

    public function update(
        User $user,
        DeliveryAssignment $deliveryAssignment
    ): bool {
        if ($user->hasRole('administrator')) {
            return true;
        }

        return $deliveryAssignment->deliveryPartner?->user_id === $user->id;
    }
}