<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\UpdateMenuItemVariantRequest;
use App\Http\Requests\StoreMenuItemVariantRequest;
use App\Models\MenuItemVariant;
use Illuminate\Http\JsonResponse;
use App\Services\RestaurantDiscoveryCache;

class MenuItemVariantController extends Controller
{
    use AuthorizesRequests;
    
    public function index(): JsonResponse
    {
        $menuItemVariant = MenuItemVariant::query()
            ->latest()
            ->get();

        return response()->json([
            'menu_items' => $menuItemVariant,
        ]);
    }

    public function store(StoreMenuItemVariantRequest $request, RestaurantDiscoveryCache $cache): JsonResponse {
        $menuItemVariant = MenuItemVariant::create(
            $request->validated()
        );

        //invalidate
        $cache->invalidate();

        return response()->json([
            'message' => 'Menu item variant created successfully.',
            'menu_item_variant' => $menuItemVariant,
        ], 201);
    }

    public function show(menuItemVariant $menuItemVariant): JsonResponse
    {
        $this->authorize('view', $menuItemVariant);

        return response()->json([
            'menu_item' => $menuItemVariant,
        ]);
    }

    public function update(
        UpdateMenuItemVariantRequest $request,
        MenuItemVariant $menuItemVariant,
        RestaurantDiscoveryCache $cache
    ): JsonResponse {
        $menuItemVariant->update(
            $request->validated()
        );

        //invalidate
        $cache->invalidate();

        return response()->json([
            'message' => 'menuItem Variant updated successfully.',
            'menu_item' => $menuItemVariant->fresh(),
        ]);
    }

    public function destroy(MenuItemVariant $menuItemVariant, RestaurantDiscoveryCache $cache): JsonResponse
    {
        $this->authorize('delete', $menuItemVariant);

        $menuItemVariant->delete();

        //invalidate
        $cache->invalidate();

        return response()->json([
            'message' => 'MenuItem Variantdeleted successfully.',
        ]);
    }
}