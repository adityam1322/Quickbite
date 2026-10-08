<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreRestaurantHourRequest;
use App\Http\Requests\UpdateRestaurantHourRequest;
use App\Models\RestaurantHours;
use App\Services\RestaurantDiscoveryCache;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class RestaurantHourController extends Controller
{

    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $restaurantHours = DB::table('restaurant_hours')
            ->latest()
            ->get();

        return response()->json([
            'restaurant_hours' => $restaurantHours,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRestaurantHourRequest $request, RestaurantDiscoveryCache $cache): JsonResponse
    {
        $restaurantHour = DB::create(
            $request->validated()
        );

        //invalidate
        $cache->invalidate();

        return response()->json([
            'message' => 'Restaurant hour created successfully.',
            'restaurant_hour' => $restaurantHour,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RestaurantHours $restaurantHour): JsonResponse
    {
        $this->authorize('view', $restaurantHour);

        return response()->json([
            'restaurant_hour' => $restaurantHour,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRestaurantHourRequest $request, RestaurantHours $restaurantHour, RestaurantDiscoveryCache $cache): JsonResponse
    {
        $restaurantHour->update(
            $request->validated()
        );

        //invalidate
        $cache->invalidate();

        return response()->json([
            'message' => 'RestaurantHour updated successfully.',
            'restaurant_hour' => $restaurantHour->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RestaurantHours $restaurantHour, RestaurantDiscoveryCache $cache): JsonResponse
    {
        $this->authorize('delete', $restaurantHour);

        $restaurantHour->delete();

        //invalidate
        $cache->invalidate();

        return response()->json([
            'message' => 'Restaurant deleted successfully.',
        ]);
    }
}
