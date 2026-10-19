<?php

namespace App\Mcp\Tools;

use App\Models\MenuItem;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Search available menu items by name, restaurant, category, and vegetarian preference.')]
class SearchMenuItems extends Tool
{
    public function handle(Request $request): Response
    {
        $page = max(1, (int) ($request->get('page') ?? 1));

        $perPage = min(
            20,
            max(1, (int) ($request->get('per_page') ?? 10))
        );

        $search = $request->get('search');
        $restaurantId = $request->get('restaurant_id');
        $categoryId = $request->get('category_id');
        $vegetarian = $request->get('vegetarian');

        if ($search !== null && ! is_string($search)) {
            return Response::text(json_encode([
                'success' => false,
                'error' => 'search must be a string.',
            ], JSON_THROW_ON_ERROR));
        }

        if ($restaurantId !== null && (! is_numeric($restaurantId) || (int) $restaurantId < 1)) {
            return Response::text(json_encode([
                'success' => false,
                'error' => 'restaurant_id must be a positive integer.',
            ], JSON_THROW_ON_ERROR));
        }

        if ($categoryId !== null && (! is_numeric($categoryId) || (int) $categoryId < 1)) {
            return Response::text(json_encode([
                'success' => false,
                'error' => 'category_id must be a positive integer.',
            ], JSON_THROW_ON_ERROR));
        }

        if ($vegetarian !== null && ! is_bool($vegetarian)) {
            return Response::text(json_encode([
                'success' => false,
                'error' => 'vegetarian must be a boolean.',
            ], JSON_THROW_ON_ERROR));
        }
        $query = MenuItem::query()
            ->where('is_available', true)
            ->with([
                'restaurant:id,name',
                'menuCategory:id,name',
            ])
            ->select([
                'id',
                'restaurant_id',
                'menu_categories_id',
                'name',
                'slug',
                'description',
                'is_vegetarian',
                'is_available',
            ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        if ($restaurantId !== null) {
            $query->where('restaurant_id', (int) $restaurantId);
        }

        if ($categoryId !== null) {
            $query->where(
                'menu_categories_id',
                (int) $categoryId
            );
        }

        if ($vegetarian !== null) {
            $query->where(
                'is_vegetarian',
                (bool) $vegetarian
            );
        }

        $items = $query
            ->orderBy('id')
            ->limit(20)
            ->get();

        return Response::text(json_encode([
            'success' => true,
            'data' => [
                'count' => $items->count(),
                'items' => $items->map(function (MenuItem $item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'is_vegetarian' => $item->is_vegetarian,
                        'restaurant' => $item->restaurant ? [
                            'id' => $item->restaurant->id,
                            'name' => $item->restaurant->name,
                        ] : null,
                        'category' => $item->menuCategory ? [
                            'id' => $item->menuCategory->id,
                            'name' => $item->menuCategory->name,
                        ] : null,
                    ];
                })->values()->all(),
            ],
        ], JSON_THROW_ON_ERROR));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema
                ->string()
                ->description('Search text for menu item name or description.')
                ->nullable(),

            'restaurant_id' => $schema
                ->integer()
                ->description('Filter by restaurant ID.')
                ->nullable(),

            'category_id' => $schema
                ->integer()
                ->description('Filter by menu category ID.')
                ->nullable(),

            'vegetarian' => $schema
                ->boolean()
                ->description('Filter vegetarian items only when true.')
                ->nullable(),
        ];
    }
}
