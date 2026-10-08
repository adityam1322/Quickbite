<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,

            'user_id' => $this->user_id,
            'restaurant_id' => $this->restaurant_id,
            'address_id' => $this->address_id,

            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax_amount,
            'delivery_fee' => $this->delivery_fee,
            'discount_amount' => $this->discount_amount,
            'total_amount' => $this->total_amount,

            'payment_status' => $this->payment_status,
            'order_status' => $this->order_status,
            'notes' => $this->notes,

            'items' => OrderItemResource::collection(
                $this->whenLoaded('items')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}