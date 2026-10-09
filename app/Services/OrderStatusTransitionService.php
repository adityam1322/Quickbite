<?php

namespace App\Services;

use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class OrderStatusTransitionService
{
    private const TRANSITIONS = [
        'placed' => ['accepted', 'rejected', 'cancelled'],
        'accepted' => ['preparing', 'cancelled'],
        'preparing' => ['ready_for_pickup', 'cancelled'],
        'ready_for_pickup' => ['picked_up'],
        'picked_up' => ['delivered'],
        'delivered' => [],
        'rejected' => [],
        'cancelled' => [],
        'refunded' => [],
    ];

    public function transition(
        Order $order,
        string $newStatus,
        User $actor,
        string $ability
    ): Order {
        $fromStatus = null;

        $updatedOrder = DB::transaction(function () use (
            $order,
            $newStatus,
            $actor,
            $ability,
            &$fromStatus
        ) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            Gate::forUser($actor)->authorize($ability, $lockedOrder);

            $fromStatus = $lockedOrder->order_status;
            $allowed = self::TRANSITIONS[$fromStatus] ?? [];

            if (! in_array($newStatus, $allowed, true)) {
                throw ValidationException::withMessages([
                    'order_status' => [
                        "Cannot change order status from {$fromStatus} to {$newStatus}.",
                    ],
                ]);
            }

            $lockedOrder->statusHistories()->create([
                'status' => $newStatus,
                'notes' => "Status changed from {$fromStatus} to {$newStatus}",
                'changed_at' => now(),
            ]);

            $lockedOrder->order_status = $newStatus;
            $lockedOrder->save();

            return $lockedOrder;
        });

        DB::afterCommit(function () use (
            $updatedOrder,
            $fromStatus,
            $newStatus,
            $actor
        ) {
            OrderStatusChanged::dispatch(
                $updatedOrder,
                $fromStatus,
                $newStatus,
                $actor->id
            );
        });

        return $updatedOrder->refresh();
    }
}
