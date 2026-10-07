<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuisine;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreCuisineRequest;
use App\Http\Requests\UpdateCuisineRequest;

class CuisineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(): JsonResponse
{
    $cuisine = Cuisine::query()
        ->latest()
        ->get();

    return response()->json([
        'Cuisine' => $cuisine,
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCuisineRequest $request): JsonRespose
    {
        $cuisine = Cuisine::create(
        $request->validated());

        return response()->json([
          'message' => 'Cuisine created successfully.',
          'Cuisine' => $cuisine,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cuisine $cuisine): JsonResponse
    {
        $this->authorize('view', $cuisine);

        return response()->json([
            'Cuisine' => $cuisine,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCuisineRequest $request, Cuisine $cuisine):JsonResponse
    {
         $cuisine->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Cuisine updated successfully.',
            'Cuisine' => $cuisine->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cuisine $cuisine): JsonResponse
    {
        $this->authorize('delete', $cuisine);

        $cuisine->delete();

        return response()->json([
            'message' => 'Cuisine deleted successfully.',
        ]);
    }
}
