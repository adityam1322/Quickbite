<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderStatusService
{
    public function changeStatus(
        int $orderId,
        string $newStatus,
        ?int $userId = null,
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use (
            $orderId,
            $newStatus,
            $userId,
            $notes
        ) {
            $order = Order::whereKey($orderId)
                ->lockForUpdate()
                ->firstOrFail();

            $oldStatus = $order->order_status;

            if ($oldStatus === $newStatus) {
                return $order;
            }

            $allowedStatuses = [
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'out_for_delivery',
                'delivered',
                'cancelled',
            ];

            if (! in_array($newStatus, $allowedStatuses, true)) {
                throw ValidationException::withMessages([
                    'order_status' => 'Invalid order status.',
                ]);
            }

            $order->order_status = $newStatus;
            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $newStatus,
                'notes' => $notes
                    ?? "Status changed from {$oldStatus} to {$newStatus}",
                'changed_at' => now(),
            ]);

            return $order;
        });
    }
}