<?php

namespace App\Jobs;

use App\Contracts\SmsProvider;
use App\Models\Order;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderConfirmation implements ShouldQueue
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

        $user = $order->user;

        $user->notify(
            new OrderConfirmationNotification($order)
        );

        $sms = app(SmsProvider::class);

        $sms->send(
            $user->phone,
            'Your QuickBite order has been confirmed.'
        );
    }
}