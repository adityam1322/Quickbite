<?php

namespace App\Mcp\Resources;

use App\Models\Restaurant;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;

#[Description('Public information about active QuickBite restaurants.')]
class RestaurantResource extends Resource
{
    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $restaurants = Restaurant::query()
            ->where('is_active', true)
            ->select([
                'id',
                'name',
                'slug',
                'description',
                'city',
                'state',
                'minimum_order_amount',
            ])
            ->limit(20)
            ->get();

        return Response::json([
            'success' => true,
            'data' => $restaurants,
        ]);
    }
}