<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $version = Cache::get('restaurants.discovery.version', 1);

        $cacheKey = 'restaurants.discovery.v' . $version . '.' . md5(
            $request->fullUrl()
        );

        $restaurants = Cache::remember(
            $cacheKey,
            now()->addMinutes(5),
            function () use ($request) {
                return QueryBuilder::for(Restaurant::class)
                    ->allowedFilters([
                        AllowedFilter::callback('cuisine', function ($query, $value) {
                            $query->whereHas('cuisines', function ($q) use ($value) {
                                $q->where('slug', $value);
                            });
                        }),

                        AllowedFilter::callback('open_now', function ($query, $value) {
                            if (! filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                                return;
                            }

                            $now = now();
                            $dayOfWeek = $now->dayOfWeek;
                            $currentTime = $now->format('H:i:s');

                            $query->whereHas('hours', function ($q) use (
                                $dayOfWeek,
                                $currentTime
                            ) {
                                $q->where('day_of_week', $dayOfWeek)
                                    ->where('is_closed', false)
                                    ->where('open_time', '<=', $currentTime)
                                    ->where('close_time', '>=', $currentTime);
                            });
                        }),

                        AllowedFilter::callback('min_price', function ($query, $value) {
                            $query->whereHas(
                                'categories.menuItems.variants',
                                function ($q) use ($value) {
                                    $q->where('price', '>=', $value);
                                }
                            );
                        }),

                        AllowedFilter::callback('max_price', function ($query, $value) {
                            $query->whereHas(
                                'categories.menuItems.variants',
                                function ($q) use ($value) {
                                    $q->where('price', '<=', $value);
                                }
                            );
                        }),

                        AllowedFilter::callback('postal_code', function ($query, $value) {
                            $query->whereHas('serviceAreas', function ($q) use ($value) {
                                $q->where('postal_code', $value)
                                    ->where('is_active', true);
                            });
                        }),
                    ])

                    ->allowedSorts([
                        'name',
                        'created_at',
                    ])

                    ->allowedIncludes([
                        'cuisines',
                        'hours',
                        'serviceAreas',
                        'categories',
                    ])

                    ->defaultSort('-created_at')

                    ->paginate(10)
                    ->appends($request->query());
            }
        );

        return RestaurantResource::collection($restaurants);
    }

    public function store(StoreRestaurantRequest $request): RestaurantResource
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('restaurants', 'public');
        }

        $restaurant = Restaurant::create($data);

        $this->invalidateRestaurantDiscoveryCache();

        return (new RestaurantResource($restaurant))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Restaurant $restaurant): RestaurantResource
    {
        $this->authorize('view', $restaurant);

        return new RestaurantResource($restaurant);
    }

    public function update(
        UpdateRestaurantRequest $request,
        Restaurant $restaurant
    ): RestaurantResource {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('restaurants', 'public');
        }

        $restaurant->update($data);

        $this->invalidateRestaurantDiscoveryCache();

        return new RestaurantResource($restaurant->fresh());
    }

    private function invalidateRestaurantDiscoveryCache(): void
    {
        Cache::increment('restaurants.discovery.version');
    }

    public function destroy(Restaurant $restaurant): JsonResponse
    {
        $this->authorize('delete', $restaurant);

        $restaurant->delete();

        $this->invalidateRestaurantDiscoveryCache();

        return response()->json([
            'message' => 'Restaurant deleted successfully.',
        ]);
    }
}