<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;

class RestaurantController extends Controller
{
    public function index(): JsonResponse
    {
        $restaurants = Restaurant::query()
            ->latest()
            ->get();

        return response()->json([
            'restaurants' => $restaurants,
        ]);
    }

    public function show(Restaurant $restaurant): JsonResponse
    {
        $this->authorize('view', $restaurant);

        return response()->json([
            'restaurant' => $restaurant,
        ]);
    }

    public function update(
        UpdateRestaurantRequest $request,
        Restaurant $restaurant
    ): JsonResponse {
        $restaurant->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Restaurant updated successfully.',
            'restaurant' => $restaurant->fresh(),
        ]);
    }

    public function destroy(Restaurant $restaurant): JsonResponse
    {
        $this->authorize('delete', $restaurant);

        $restaurant->delete();

        return response()->json([
            'message' => 'Restaurant deleted successfully.',
        ]);
    }
}