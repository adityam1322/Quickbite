<?php

namespace App\Mcp\Resources;

use App\Models\MenuItem;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;

#[Description('Public menu information for available QuickBite menu items.')]
class MenuResource extends Resource
{
    public function handle(Request $request): Response
    {
        $items = MenuItem::query()
            ->where('is_available', true)
            ->whereHas('restaurant', function ($query) {
                $query->where('is_active', true);
            })
            ->select([
                'id',
                'restaurant_id',
                'name',
                'slug',
                'description',
                'is_vegetarian',
                'is_available',
            ])
            ->limit(20)
            ->get();

        return Response::json([
            'success' => true,
            'data' => $items,
        ]);
    }
}