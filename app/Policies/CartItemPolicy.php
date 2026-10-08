<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;

class CartItemPolicy
{
    public function update(
        User $user,
        CartItem $cartItem,
        Cart $cart
    ): bool {
        return $cartItem->cart_id === $cart->id;
    }

    public function delete(
        User $user,
        CartItem $cartItem,
        Cart $cart
    ): bool {
        return $cartItem->cart_id === $cart->id;
    }
}