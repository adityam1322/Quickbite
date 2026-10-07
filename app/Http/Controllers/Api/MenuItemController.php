<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $menuItems = MenuItem::query()
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->search;

                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'ILIKE', "%{$search}%")
                            ->orWhere('slug', 'ILIKE', "%{$search}%")
                            ->orWhere('discription', 'ILIKE', "%{$search}%");
                    });
                }
            )
            ->latest()
            ->paginate(10);

        return MenuItemResource::collection($menuItems);
    }

    public function store(StoreMenuItemRequest $request)
    {
        $menuItem = MenuItem::create(
            $request->validated()
        );

        return (new MenuItemResource($menuItem))
            ->response()
            ->setStatusCode(201);
    }

    public function show(MenuItem $menuItem)
    {
        $this->authorize('view', $menuItem);

        return new MenuItemResource($menuItem);
    }

    public function update(
        UpdateMenuItemRequest $request,
        MenuItem $menuItem
    ) {
        $this->authorize('update', $menuItem);

        $menuItem->update(
            $request->validated()
        );

        return new MenuItemResource($menuItem->fresh());
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->authorize('delete', $menuItem);

        $menuItem->delete();

        return response()->json([
            'message' => 'Menu item deleted successfully.',
        ]);
    }
}