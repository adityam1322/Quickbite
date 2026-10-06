<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\User;

class RestaurantPolicy
{
    /**
     * Determine whether the user can view the restaurant.
     */
    public function view(
        User $user,
        Restaurant $restaurant
    ): bool {
        return $user->can('restaurants.view');
    }

    /**
     * Determine whether the user can update the restaurant.
     */
    public function update(
        User $user,
        Restaurant $restaurant
    ): bool {
        // User must have the permission first.
        if (! $user->can('restaurants.update')) {
            return false;
        }

        // Administrator can update any restaurant.
        if ($user->hasRole('administrator')) {
            return true;
        }

        // Restaurant staff can update only their own restaurant.
        return $restaurant->staff()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the restaurant.
     */
    public function delete(
        User $user,
        Restaurant $restaurant
    ): bool {
        return $user->hasRole('administrator')
            && $user->can('restaurants.delete');
    }
}