<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;

class MenuItemController extends Controller
{
    public function index(): JsonResponse
    {
        $menuItems = MenuItem::query()
            ->latest()
            ->get();

        return response()->json([
            'menu_items' => $menuItems,
        ]);
    }

    public function show(MenuItem $menuItem): JsonResponse
    {
        $this->authorize('view', $menuItem);

        return response()->json([
            'menu_item' => $menuItem,
        ]);
    }

    public function update(
        UpdateMenuItemRequest $request,
        MenuItem $menuItem
    ): JsonResponse {
        $menuItem->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Menu item updated successfully.',
            'menu_item' => $menuItem->fresh(),
        ]);
    }

    public function destroy(MenuItem $menuItem): JsonResponse
    {
        $this->authorize('delete', $menuItem);

        $menuItem->delete();

        return response()->json([
            'message' => 'Menu item deleted successfully.',
        ]);
    }
}