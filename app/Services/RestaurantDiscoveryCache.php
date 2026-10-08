<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class RestaurantDiscoveryCache
{
    public function invalidate(): void
    {
        Cache::increment('restaurants.discovery.version');
    }
}