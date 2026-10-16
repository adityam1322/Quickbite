<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\DeliveryAssignmentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\DeliveryAssignmentService;

class AssignDeliveryPartner implements ShouldQueue
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

    public function handle(
        DeliveryAssignmentService $assignmentService
    ): void {
        $order = Order::findOrFail($this->orderId);

        $assignment = $assignmentService->assign($order);

        if (! $assignment) {
            return;
        }

        $assignment->deliveryPartner->user->notify(
            new DeliveryAssignmentNotification($order)
        );
    }
}
