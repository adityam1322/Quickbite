<?php

namespace App\Mcp\Tools;

use App\Models\Restaurant;
use App\Services\McpToolLogger;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Stringable;

#[Name('search_restaurants')]
#[Description('Search active restaurants by name, city, or cuisine. Returns a bounded paginated result set.')]
class SearchRestaurants extends Tool
{
    public function handle(Request $request): Stringable|string
{
    $area = $request['area'] ?? null;
    $city = $request['city'] ?? null;
    $cuisine = $request['cuisine'] ?? null;
    $max_minimum_order = $request['max_minimum_order'] ?? null;

    $start = microtime(true);

    try {
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

        if ($city) {
            $query->where('city', $city);
        }

        if ($area) {
            $query->whereHas('serviceAreas', function ($serviceAreaQuery) use ($area) {
                $serviceAreaQuery->where('area_name', $area);
            });
        }

        if ($cuisine) {
            $query->whereHas('cuisines', function ($cuisineQuery) use ($cuisine) {
                $cuisineQuery->where('name', $cuisine);
            });
        }

        if ($max_minimum_order !== null) {
            $query->where(
                'minimum_order_amount',
                '<=',
                $max_minimum_order
            );
        }

        $restaurants = $query
            ->orderBy('id')
            ->limit(20)
            ->get();

        $duration = (int) round(
            (microtime(true) - $start) * 1000
        );

        app(McpToolLogger::class)->log(
            'search_restaurants',
            $duration,
            'success'
        );

        return json_encode([
            'restaurants' => $restaurants->map(function (Restaurant $restaurant) {
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
            })->values()->all(),
        ], JSON_THROW_ON_ERROR);

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

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema
                ->string()
                ->description('Restaurant name or keyword to search for.')
                ->nullable(),

            'city' => $schema
                ->string()
                ->description('City in which to search.')
                ->nullable(),

            'limit' => $schema
                ->integer()
                ->description('Maximum number of restaurants to return. Maximum is 20.')
                ->nullable(),
        ];
    }
}