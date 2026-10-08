<?php

namespace App\Services;

use App\Models\Cart;

class OrderCalculationService
{
    public function calculate(Cart $cart): array
    {
        $subtotal = $cart->items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        $taxAmount = $subtotal * 0.05;

        $deliveryFee = 40;

        $discountAmount = 0;

        $totalAmount =
            $subtotal
            + $taxAmount
            + $deliveryFee
            - $discountAmount;

        return [
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'delivery_fee' => $deliveryFee,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
        ];
    }
}