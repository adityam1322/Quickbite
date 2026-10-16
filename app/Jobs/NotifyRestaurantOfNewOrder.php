<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\RestaurantOrderAlertNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyRestaurantOfNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function __construct(
        public int $orderId
    ) {}

    public function handle(): void
    {
        $order = Order::findOrFail($this->orderId);

        $restaurant = $order->restaurant;

        if (! $restaurant) {
            return;
        }

        foreach ($restaurant->staff as $staff) {
            $staff->user->notify(
                new RestaurantOrderAlertNotification($order)
            );
        }
    }
}
