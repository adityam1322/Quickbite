<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'menu_categories_id' => $this->menu_categories_id,
            'restaurant_id' => $this->restaurant_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'discription' => $this->discription,
            'is_vegetarian' => $this->is_vegetarian,
            'is_available' => $this->is_available,
            'created_at' => $this->created_at,

            'category' => $this->whenLoaded('category'),
            'restaurant' => $this->whenLoaded('restaurant'),
            'variants' => $this->whenLoaded('variants'),
            'images' => $this->whenLoaded('images'),
        ];
    }
}