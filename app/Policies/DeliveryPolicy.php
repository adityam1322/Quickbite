<?php

namespace App\Policies;

use App\Models\DeliveryAssignment;
use App\Models\User;

class DeliveryPolicy
{
    public function view(User $user, DeliveryAssignment $delivery): bool
    {
        if ($user->hasRole('administrator')) {
            return $user->can('deliveries.view');
        }

        return $user->can('deliveries.view')
            && $delivery->deliveryPartner?->user_id === $user->id;
    }

    public function update(User $user, DeliveryAssignment $delivery): bool
    {
        if (! $user->can('deliveries.update')) {
            return false;
        }

        if ($user->hasRole('administrator')) {
            return true;
        }

        return $delivery->deliveryPartner?->user_id === $user->id;
    }
}