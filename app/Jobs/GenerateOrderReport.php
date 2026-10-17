<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class GenerateOrderReport implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $restaurantId,
        public string $from,
        public string $to,
        public string $format = 'csv',
    ) {
    }

    public function handle(): void
    {
        $orders = Order::query()
            ->where('restaurant_id', $this->restaurantId)
            ->whereBetween('created_at', [
                $this->from . ' 00:00:00',
                $this->to . ' 23:59:59',
            ])
            ->orderBy('created_at')
            ->get();

        $filename = 'reports/orders-' . now()->format('YmdHis') . '.csv';

        $handle = fopen(storage_path('app/' . $filename), 'w');

        fputcsv($handle, [
            'Order ID',
            'Customer ID',
            'Restaurant ID',
            'Order Status',
            'Payment Status',
            'Created At',
        ]);

        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->id,
                $order->user_id,
                $order->restaurant_id,
                $order->order_status,
                $order->payment_status,
                $order->created_at,
            ]);
        }

        fclose($handle);
    }
}