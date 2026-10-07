<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantHour;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreRestaurantHourRequest;

class RestaurantHourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(): JsonResponse
{
    $restaurantHours = RestaurantHour::query()
        ->latest()
        ->get();

    return response()->json([
        'restaurant_hours' => $restaurantHours,
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRestaurantHourRequest $request): JsonRespose
    {
        $restaurantHour = RestaurantHour::create(
        $request->validated());

        return response()->json([
          'message' => 'Restaurant hour created successfully.',
          'restaurant_hour' => $restaurantHour,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RestaurantHour $restaurantHour): JsonResponse
    {
        $this->authorize('view', $restaurantHour);

        return response()->json([
            'restaurant_hour' => $restaurantHour,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRestaurantHourRequest $request, RestaurantHour $restaurantHour):JsonResponse
    {
         $restaurantHour->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'RestaurantHour updated successfully.',
            'restaurant_hour' => $restaurantHour->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RestaurantHour $restaurantHour): JsonResponse
    {
        $this->authorize('delete', $restaurantHour);

        $restaurantHour->delete();

        return response()->json([
            'message' => 'Restaurant deleted successfully.',
        ]);
    }
}
