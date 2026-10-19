<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use App\Models\MenuItem;
use Stringable;

class SearchMenuItems implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search active menu items by restaurant, category, vegetarian preference, and price.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $restaurantId = $request->input('restaurant_id');
        $categoryId = $request->input('category_id');
        $vegetarian = $request->input('vegetarian');
        $maxPrice = $request->input('max_price');
        $search = $request->input('search');

        $query = MenuItem::query()
            ->where('is_avialable', true)
            ->with([
                'prices',
                'category:id,name',
            ])
            ->select([
                'id',
                'restaurant_id',
                'menu_categories_id',
                'name',
                'slug',
                'is_vegetarian',
                'is_avialable',
            ]);

        /*
         * Filter by restaurant.
         */
        if ($restaurantId !== null) {
            $query->where('restaurant_id', $restaurantId);
        }

        /*
         * Filter by menu category.
         */
        if ($categoryId !== null) {
            $query->where('menu_categories_id', $categoryId);
        }

        /*
         * Filter vegetarian items.
         */
        if ($vegetarian !== null) {
            $query->where('is_vegetarian', $vegetarian);
        }

        /*
         * Search by menu item name.
         */
        if ($search) {
            $query->where('name', 'ilike', '%' . $search . '%');
        }

        /*
         * Keep the result bounded.
         */
        $items = $query
            ->orderBy('id')
            ->limit(30)
            ->get();

        /*
         * Apply the price filter using the current
         * menu item price.
         */
        $items = $items->filter(function (MenuItem $item) use ($maxPrice) {
            if ($maxPrice === null) {
                return true;
            }

            $currentPrice = $item->prices
                ->filter(function ($price) {
                    $today = now()->toDateString();

                    return $price->effective_from <= $today
                        && (
                            $price->effective_until === null
                            || $price->effective_until >= $today
                        );
                })
                ->sortByDesc('effective_from')
                ->first();

            return $currentPrice !== null
                && (float) $currentPrice->price <= (float) $maxPrice;
        });

        /*
         * Return only the data required by the AI.
         */
        $result = $items->map(function (MenuItem $item) {
            $today = now()->toDateString();

            $currentPrice = $item->prices
                ->filter(function ($price) use ($today) {
                    return $price->effective_from <= $today
                        && (
                            $price->effective_until === null
                            || $price->effective_until >= $today
                        );
                })
                ->sortByDesc('effective_from')
                ->first();

            return [
                'id' => $item->id,
                'restaurant_id' => $item->restaurant_id,
                'category_id' => $item->menu_categories_id,
                'name' => $item->name,
                'vegetarian' => $item->is_vegetarian,
                'price' => $currentPrice?->price,
                'currency' => $currentPrice?->currency,
            ];
        })->values()->all();

        return json_encode([
            'menu_items' => $result,
        ]);
    }


    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
        'restaurant_id' => $schema->integer()->nullable(),

        'category_id' => $schema->integer()->nullable(),

        'vegetarian' => $schema->boolean()->nullable(),

        'max_price' => $schema->number()->nullable(),

        'search' => $schema->string()->nullable(),
       ];
    }
}
