<?php

namespace App\Providers;

use App\Models\DeliveryAssignment;
use App\Policies\DeliveryPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(
            DeliveryAssignment::class,
            DeliveryPolicy::class
        );
    }
}