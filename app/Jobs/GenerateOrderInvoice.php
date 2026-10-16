<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateOrderInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function __construct(public int $orderId){
        
    }

    public function handle(): void
    {
        $order = Order::findOrFail($this->orderId);

        $invoicePath = 'invoices/' . $order->order_number . '.txt';

        if (Storage::disk('local')->exists($invoicePath)) {
            return;
        }

        $invoice = "QuickBite Invoice\n";
        $invoice .= "-------------------------\n";
        $invoice .= "Order Number: {$order->order_number}\n";
        $invoice .= "Subtotal: {$order->subtotal}\n";
        $invoice .= "Tax: {$order->tax_amount}\n";
        $invoice .= "Delivery Fee: {$order->delivery_fee}\n";
        $invoice .= "Discount: {$order->discount_amount}\n";
        $invoice .= "Total: {$order->total_amount}\n";

        Storage::disk('local')->put(
            $invoicePath,
            $invoice
        );
    }
}