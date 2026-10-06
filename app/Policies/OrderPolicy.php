<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(
        User $user,
        Order $order
    ): bool {
        if ($user->hasRole('administrator')) {
            return $user->can('orders.view');
        }

        if (
            $user->hasRole('customer') &&
            $order->user_id === $user->id
        ) {
            return $user->can('orders.view');
        }

        if (
            $user->hasRole('restaurant_staff') &&
            $order->restaurant
                ->staff()
                ->where('user_id', $user->id)
                ->exists()
        ) {
            return $user->can('orders.view');
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('customer')
            && $user->can('orders.create');
    }

    public function manage(
        User $user,
        Order $order
    ): bool {
        if (! $user->can('orders.manage')) {
            return false;
        }

        if ($user->hasRole('administrator')) {
            return true;
        }

        return $order->restaurant
            ->staff()
            ->where('user_id', $user->id)
            ->exists();
    }
}