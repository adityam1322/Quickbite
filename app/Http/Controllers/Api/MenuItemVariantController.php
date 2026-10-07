<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateMenuItemVariantRequest;
use App\Http\Requests\StoreMenuItemVariantRequest;
use App\Models\MenuItemVariant;
use Illuminate\Http\JsonResponse;

class MenuItemVariantController extends Controller
{
    public function index(): JsonResponse
    {
        $menuItemVariant = MenuItemVariant::query()
            ->latest()
            ->get();

        return response()->json([
            'menu_items' => $menuItemVariant,
        ]);
    }

    public function store(
        StoreMenuItemVariantRequest $request
    ): JsonResponse {
        $menuItemVariant = MenuItemVariant::create(
            $request->validated()
        );

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
        MenuItemVariant $menuItemVariant
    ): JsonResponse {
        $menuItemVariant->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'menuItem Variant updated successfully.',
            'menu_item' => $menuItemVariant->fresh(),
        ]);
    }

    public function destroy(MenuItemVariant $menuItemVariant): JsonResponse
    {
        $this->authorize('delete', $menuItemVariant);

        $menuItemVariant->delete();

        return response()->json([
            'message' => 'MenuItem Variantdeleted successfully.',
        ]);
    }
}