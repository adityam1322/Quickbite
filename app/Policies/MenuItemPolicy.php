<?php

namespace App\Policies;

use App\Models\MenuItem;
use App\Models\User;

class MenuItemPolicy
{
    public function view(
        User $user,
        MenuItem $menuItem
    ): bool {
        return $user->can('menus.view');
    }

    public function update(
        User $user,
        MenuItem $menuItem
    ): bool {
        if (! $user->can('menus.update')) {
            return false;
        }

        if ($user->hasRole('administrator')) {
            return true;
        }

        return $menuItem->restaurant
            ->staff()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function delete(
        User $user,
        MenuItem $menuItem
    ): bool {
        if (! $user->can('menus.delete')) {
            return false;
        }

        if ($user->hasRole('administrator')) {
            return true;
        }

        return $menuItem->restaurant
            ->staff()
            ->where('user_id', $user->id)
            ->exists();
    }
}