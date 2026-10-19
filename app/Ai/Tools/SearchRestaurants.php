<?php

namespace App\Ai\Tools;

use App\Models\Restaurant;
use App\Services\McpToolLogger;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchRestaurants implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search active restaurants by area, city, cuisine, and minimum order amount.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $start = microtime(true);

        try {
            $area = $request['area'] ?? null;
            $city = $request['city'] ?? null;
            $cuisine = $request['cuisine'] ?? null;
            $maxMinimumOrder = $request['max_minimum_order'] ?? null;

            $query = Restaurant::query()
                ->where('is_active', true)
                ->with([
                    'serviceAreas:id,restaurant_id,area_name,city',
                    'cuisines:id,name',
                ])
                ->select([
                    'id',
                    'name',
                    'slug',
                    'city',
                    'minimum_order_amount',
                ]);

            /*
             * Filter by city.
             */
            if ($city) {
                $query->where('city', $city);
            }

            /*
             * Filter by area through restaurant_service_areas.
             */
            if ($area) {
                $query->whereHas('serviceAreas', function ($serviceAreaQuery) use ($area) {
                    $serviceAreaQuery->where('area_name', $area);
                });
            }

            /*
             * Filter by cuisine.
             */
            if ($cuisine) {
                $query->whereHas('cuisines', function ($cuisineQuery) use ($cuisine) {
                    $cuisineQuery->where('name', $cuisine);
                });
            }

            /*
             * Filter by minimum order budget.
             */
            if ($maxMinimumOrder !== null) {
                $query->where(
                    'minimum_order_amount',
                    '<=',
                    $maxMinimumOrder
                );
            }

            /*
             * Keep result size bounded.
             */
            $restaurants = $query
                ->orderBy('id')
                ->limit(20)
                ->get();

            $result = [
                'restaurants' => $restaurants->map(
                    function (Restaurant $restaurant) {
                        return [
                            'id' => $restaurant->id,
                            'name' => $restaurant->name,
                            'city' => $restaurant->city,
                            'minimum_order_amount' => $restaurant->minimum_order_amount,
                            'service_areas' => $restaurant->serviceAreas
                                ->map(fn ($serviceArea) => $serviceArea->area_name)
                                ->values()
                                ->all(),
                            'cuisines' => $restaurant->cuisines
                                ->pluck('name')
                                ->values()
                                ->all(),
                        ];
                    }
                )->values()->all(),
            ];

            $duration = (int) round(
                (microtime(true) - $start) * 1000
            );

            app(McpToolLogger::class)->log(
                'search_restaurants',
                $duration,
                'success'
            );

            return json_encode(
                $result,
                JSON_THROW_ON_ERROR
            );
        } catch (\Throwable $e) {
            $duration = (int) round(
                (microtime(true) - $start) * 1000
            );

            app(McpToolLogger::class)->log(
                'search_restaurants',
                $duration,
                'error'
            );

            throw $e;
        }
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'area' => $schema
                ->string()
                ->nullable(),

            'city' => $schema
                ->string()
                ->nullable(),

            'cuisine' => $schema
                ->string()
                ->nullable(),

            'max_minimum_order' => $schema
                ->number()
                ->nullable(),

            'vegetarian' => $schema
                ->boolean()
                ->nullable(),
        ];
    }
}