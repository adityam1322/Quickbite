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



    public function accept(User $user, Order $order): bool
    {
        return $user->hasRole('restaurant_staff')
            && $user->can('orders.manage')
            && $this->isRestaurantStaff($user, $order);
    }

    public function reject(User $user, Order $order): bool
    {
        return $this->accept($user, $order);
    }

    public function markReady(User $user, Order $order): bool
    {
        return $this->accept($user, $order);
    }

    public function pickup(User $user, Order $order): bool
    {
        return $user->hasRole('delivery_partner')
            && $user->can('orders.manage')
            && $order->deliveryAssignments()
            ->where('delivery_partner_id', $user->id)
            ->exists();
    }

    public function deliver(User $user, Order $order): bool
    {
        return $this->pickup($user, $order);
    }

    public function cancel(User $user, Order $order): bool
    {
        if ($user->hasRole('administrator')) {
            return $user->can('orders.manage');
        }

        return $user->hasRole('customer')
            && $order->user_id === $user->id
            && $user->can('orders.manage');
    }

    public function refund(User $user, Order $order): bool
    {
        return $user->hasRole('administrator')
            && $user->can('orders.manage');
    }

    private function isRestaurantStaff(
        User $user,
        Order $order
    ): bool {
        return $order->restaurant
            ->staff()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function startPreparing(
        User $user,
        Order $order
    ): bool {
        return $this->accept($user, $order);
    }
}
