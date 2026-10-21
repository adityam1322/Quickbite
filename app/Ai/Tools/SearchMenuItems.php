<?php

namespace App\Ai\Tools;

use App\Models\MenuItem;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchMenuItems implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search active menu items by restaurant, category, vegetarian preference, name, and maximum price.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $restaurantId = $request['restaurant_id'] ?? null;
        $categoryId = $request['category_id'] ?? null;
        $vegetarian = $request['vegetarian'] ?? null;
        $maxPrice = $request['max_price'] ?? null;
        $search = $request['search'] ?? null;

        $query = MenuItem::query()
            ->where('is_available', true)
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
                'is_available',
            ]);

        /*
         * Filter by restaurant.
         */
        if ($restaurantId !== null) {
            $query->where('restaurant_id', $restaurantId);
        }

        /*
         * Filter by category.
         */
        if ($categoryId !== null) {
            $query->where('menu_categories_id', $categoryId);
        }

        /*
         * Filter vegetarian.
         */
        if ($vegetarian !== null) {
            $query->where('is_vegetarian', $vegetarian);
        }

        /*
         * Search by item name.
         */
        if ($search !== null && trim($search) !== '') {
            $query->where(
                'name',
                'ilike',
                '%' . trim($search) . '%'
            );
        }

        /*
         * Get menu items.
         */
        $items = $query
            ->orderBy('id')
            ->limit(30)
            ->get();

        /*
         * Prepare result.
         */
        $result = [];

        foreach ($items as $item) {

            /*
             * Find current active price.
             */
            $currentPrice = $item->prices
                ->filter(function ($price) {

                    return $price->effective_from <= today()
                        && (
                            $price->effective_until === null
                            || $price->effective_until >= today()
                        );
                })
                ->sort(function ($a, $b) {

                    /*
                     * Latest effective_from first.
                     */
                    if ($a->effective_from != $b->effective_from) {
                        return $a->effective_from < $b->effective_from
                            ? 1
                            : -1;
                    }

                    /*
                     * If same date, latest ID first.
                     */
                    return $a->id < $b->id ? 1 : -1;
                })
                ->first();

            /*
             * If max price is requested,
             * skip items without a valid current price.
             */
            if ($maxPrice !== null) {

                if ($currentPrice === null) {
                    continue;
                }

                if ((float) $currentPrice->price > (float) $maxPrice) {
                    continue;
                }
            }

            /*
             * Add item to result.
             */
            $result[] = [
                'id' => $item->id,
                'restaurant_id' => $item->restaurant_id,
                'category_id' => $item->menu_categories_id,
                'category_name' => $item->category?->name,
                'name' => $item->name,
                'vegetarian' => (bool) $item->is_vegetarian,
                'price' => $currentPrice !== null
                    ? (float) $currentPrice->price
                    : null,
                'currency' => $currentPrice?->currency,
            ];
        }

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
            'restaurant_id' => $schema
                ->integer()
                ->nullable(),

            'category_id' => $schema
                ->integer()
                ->nullable(),

            'vegetarian' => $schema
                ->boolean()
                ->nullable(),

            'max_price' => $schema
                ->number()
                ->nullable(),

            'search' => $schema
                ->string()
                ->nullable(),
        ];
    }
}