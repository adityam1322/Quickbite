<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Services\RestaurantDiscoveryCache;

class MenuItemController extends Controller
{
    use AuthorizesRequests;

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

    public function store(StoreMenuItemRequest $request, RestaurantDiscoveryCache $cache )
    {
        $menuItem = MenuItem::create(
            $request->validated()
        );
        
        //invalidate
        $cache->invalidate();

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
        MenuItem $menuItem,
        RestaurantDiscoveryCache $cache
    ) {
        $this->authorize('update', $menuItem);

        $menuItem->update(
            $request->validated()
        );

        //invalidate
        $cache->invalidate();

        return new MenuItemResource($menuItem->fresh());
    }

    public function destroy(MenuItem $menuItem, RestaurantDiscoveryCache $cache)
    {
        $this->authorize('delete', $menuItem);

        $menuItem->delete();

        //invalidate
        $cache->invalidate();

        return response()->json([
            'message' => 'Menu item deleted successfully.',
        ]);
    }
}