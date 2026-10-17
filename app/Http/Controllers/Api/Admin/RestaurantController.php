<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;

class RestaurantController extends Controller
{
    public function index(): JsonResponse
    {
        $restaurants = Restaurant::query()
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $restaurants,
        ]);
    }

    public function show(Restaurant $restaurant): JsonResponse
    {
        return response()->json([
            'data' => $restaurant,
        ]);
    }

    public function updateStatus(
        Restaurant $restaurant
    ): JsonResponse {
        $restaurant->update([
            'is_active' => ! $restaurant->is_active,
        ]);

        return response()->json([
            'message' => 'Restaurant status updated successfully.',
            'data' => $restaurant->fresh(),
        ]);
    }
}