<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CancelUnacceptedOrders extends Command
{
    protected $signature = 'quickbite:cancel-unaccepted-orders';

    protected $description = 'Cancel orders that were not accepted on time';

    public function handle(): int
    {
        $orders = Order::where('order_status', 'placed')
            ->where('created_at', '<', now()->subMinutes(15))
            ->get();

        foreach ($orders as $order) {
            $order->update([
                'order_status' => 'cancelled',
            ]);
        }

        $this->info("Cancelled {$orders->count()} unaccepted orders.");

        return self::SUCCESS;
    }
}