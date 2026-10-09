<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RefundService
{
    public function refund(
        Order $order,
        string $reason
    ): Refund {
        return DB::transaction(function () use ($order, $reason) {

            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            $payment = Payment::query()
                ->where('order_id', $lockedOrder->id)
                ->where('status', 'succeeded')
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                throw ValidationException::withMessages([
                    'payment' => ['No successful payment found for this order.'],
                ]);
            }

            if (in_array($lockedOrder->order_status, [
                'refunded',
                'cancelled',
                'rejected',
            ], true)) {
                throw ValidationException::withMessages([
                    'order' => ['This order cannot be refunded in its current state.'],
                ]);
            }

            $alreadyRefunded = Refund::query()
                ->where('payment_id', $payment->id)
                ->where('status', 'succeeded')
                ->exists();

            if ($alreadyRefunded) {
                throw ValidationException::withMessages([
                    'refund' => ['This payment has already been refunded.'],
                ]);
            }

            $refund = Refund::query()->create([
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'reson' => $reason,
                'status' => 'pending',
            ]);

            // Provider integration will be added in the next step.
            return $refund;
        });
    }
}