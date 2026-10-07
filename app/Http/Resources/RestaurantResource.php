<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'phone' => $this->phone,
            'email' => $this->email,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ];
    }
}