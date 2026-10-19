<?php

namespace App\Mcp\Tools;

use App\Models\Restaurant;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;

#[Name('get_restaurant_menu')]
#[Description('Get available menu items for a specific restaurant.')]
class GetRestaurantMenu extends Tool
{
    public function handle(array $arguments): array
    {

        $page = max(1, (int) ($arguments['page'] ?? 1));

        $perPage = min(
            20,
            max(1, (int) ($arguments['per_page'] ?? 10))
        );
        $restaurantId = $arguments['restaurant_id'] ?? null;

        if (! is_numeric($restaurantId)) {
            return [
                'success' => false,
                'message' => 'A valid restaurant_id is required.',
            ];
        }

        $restaurant = Restaurant::query()
            ->where('is_active', true)
            ->find((int) $restaurantId);

        if (! $restaurant) {
            return [
                'success' => false,
                'message' => 'Restaurant not found.',
            ];
        }

        $menuItems = $restaurant->menuItems()
            ->where('is_available', true)
            ->select([
                'id',
                'restaurant_id',
                'menu_category_id',
                'name',
                'slug',
                'description',
                'is_vegetarian',
                'is_available',
            ])
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return [
            'success' => true,
            'data' => [
                'restaurant' => [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                ],
                'menu_items' => $menuItems->toArray(),
            ],
        ];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'restaurant_id' => $schema
                ->integer()
                ->description('The ID of the restaurant.')
                ->required(),

            'page' => $schema
                ->integer()
                ->description('Page number, starting from 1.')
                ->nullable(),

            'per_page' => $schema
                ->integer()
                ->description('Number of menu items per page. Maximum 20.')
                ->nullable(),
        ];
    }
}
