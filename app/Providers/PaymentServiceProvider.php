<?php

namespace App\Providers;

use App\Contracts\PaymentProviderInterface;
use App\Services\Payments\FakePaymentProvider;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PaymentProviderInterface::class,
            FakePaymentProvider::class
        );
    }
}