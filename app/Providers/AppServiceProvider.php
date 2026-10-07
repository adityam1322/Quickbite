<?php

namespace App\Providers;

use App\Models\DeliveryAssignment;
use App\Policies\DeliveryPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        RateLimiter::for('login', function (Request $request) {
        return Limit::perMinute(5)->by(
            $request->ip()
        );
      });
      
        Gate::policy(
            DeliveryAssignment::class,
            DeliveryPolicy::class
        );
    }
}