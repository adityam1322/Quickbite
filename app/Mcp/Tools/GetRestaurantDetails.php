<?php

namespace App\Mcp\Tools;

use App\Models\MenuItem;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get detailed information about a specific menu item.')]
class GetMenuItemDetails extends Tool
{
    public function handle(Request $request): Response
    {
        $menuItemId = $request->get('menu_item_id');

        if (! is_numeric($menuItemId)) {
            return Response::text(json_encode([
                'success' => false,
                'message' => 'A valid menu_item_id is required.',
            ]));
        }

        $menuItem = MenuItem::query()
            ->where('id', (int) $menuItemId)
            ->with([
                'restaurant:id,name,city',
                'menuCategory:id,name',
                'variants:id,menu_item_id,name,price',
            ])
            ->first();

        if (! $menuItem) {
            return Response::text(json_encode([
                'success' => false,
                'message' => 'Menu item not found.',
            ]));
        }

        return Response::text(json_encode([
            'success' => true,
            'data' => [
                'id' => $menuItem->id,
                'name' => $menuItem->name,
                'slug' => $menuItem->slug,
                'description' => $menuItem->description,
                'is_vegetarian' => $menuItem->is_vegetarian,
                'is_available' => $menuItem->is_available,

                'restaurant' => $menuItem->restaurant ? [
                    'id' => $menuItem->restaurant->id,
                    'name' => $menuItem->restaurant->name,
                    'city' => $menuItem->restaurant->city,
                ] : null,

                'category' => $menuItem->menuCategory ? [
                    'id' => $menuItem->menuCategory->id,
                    'name' => $menuItem->menuCategory->name,
                ] : null,

                'variants' => $menuItem->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'price' => $variant->price,
                    ];
                })->values()->all(),
            ],
        ], JSON_THROW_ON_ERROR));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'menu_item_id' => $schema
                ->integer()
                ->description('The ID of the menu item.')
                ->required(),
        ];
    }
}