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
        public int $userId,
        public int $restaurantId,
        public string $from,
        public string $to,
        public string $format = 'csv',
    ) {}

    public function handle(): void
    {
        $orders = Order::query()
            ->select([
                'id',
                'order_number',
                'restaurant_id',
                'payment_status',
                'order_status',
                'subtotal',
                'tax_amount',
                'delivery_fee',
                'discount_amount',
                'total_amount',
                'created_at',
            ])
            ->where('restaurant_id', $this->restaurantId)
            ->whereBetween('created_at', [
                $this->from . ' 00:00:00',
                $this->to . ' 23:59:59',
            ])
            ->orderBy('id')
            ->get();

        $extension = $this->format === 'xlsx' ? 'xlsx' : 'csv';

        $filePath = 'exports/orders-' . now()->format('Y-m-d-His') . '.' . $extension;

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
