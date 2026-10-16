<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateOrderExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function __construct(
        public int $userId
    ) {
    }

    public function handle(): void
    {
        $orders = Order::query()
            ->select([
                'id',
                'order_number',
                'payment_status',
                'order_status',
                'subtotal',
                'tax_amount',
                'delivery_fee',
                'discount_amount',
                'total_amount',
                'created_at',
            ])
            ->orderBy('id')
            ->get();

        $filePath = 'exports/orders-' . now()->format('Y-m-d-His') . '.csv';

        $csv = "Order ID,Order Number,Payment Status,Order Status,Subtotal,Tax,Delivery Fee,Discount,Total,Created At\n";

        foreach ($orders as $order) {
            $csv .= implode(',', [
                $order->id,
                '"' . str_replace('"', '""', $order->order_number) . '"',
                $order->payment_status,
                $order->order_status,
                $order->subtotal,
                $order->tax_amount,
                $order->delivery_fee,
                $order->discount_amount,
                $order->total_amount,
                $order->created_at,
            ]) . "\n";
        }

        Storage::disk('local')->put($filePath, $csv);
    }
}