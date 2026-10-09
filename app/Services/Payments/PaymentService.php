<?php

namespace App\Services\Payments;

use App\Contracts\PaymentProviderInterface;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(
        private PaymentProviderInterface $provider
    ) {}

    public function pay(
        Order $order,
        string $idempotencyKey
    ): Payment {
        if (trim($idempotencyKey) === '') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'idempotency_key' => ['Idempotency key is required.'],
            ]);
        }

        return DB::transaction(function () use ($order, $idempotencyKey) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            $existingAttempt = \App\Models\PaymentAttempt::query()
                ->where('idempotency_key', $idempotencyKey)
                ->with('payment')
                ->first();

            if ($existingAttempt) {
                if ($existingAttempt->payment->order_id !== $lockedOrder->id) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'idempotency_key' => ['This key has already been used.'],
                    ]);
                }

                if ($existingAttempt->status === 'succeeded') {
                    return $existingAttempt->payment;
                }

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment' => ['This payment attempt already exists. Check its status before retrying.'],
                ]);
            }

            if ($lockedOrder->payment_status === 'paid') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment' => ['This order has already been paid.'],
                ]);
            }

            if (! in_array($lockedOrder->order_status, ['placed', 'accepted'], true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'order' => ['This order cannot be paid in its current state.'],
                ]);
            }

            $payment = $lockedOrder->payments()->create([
                'payment_method' => 'fake',
                'amount' => $lockedOrder->total_amount,
                'currency' => 'INR',
                'status' => 'pending',
            ]);

            $attemptNumber = $payment->attempts()->count() + 1;

            $attempt = $payment->attempts()->create([
                'idempotency_key' => $idempotencyKey,
                'attempt_number' => $attemptNumber,
                'amount' => $lockedOrder->total_amount,
                'status' => 'pending',
                'attempted-at' => now(),
            ]);

            $result = $this->provider->charge(
                number_format((float) $lockedOrder->total_amount, 2, '.', ''),
                'INR',
                $idempotencyKey
            );

            $attempt->update([
                'transaction_id' => $result['transaction_id'],
                'status' => $result['status'],
                'responce_massage' => 'Fake payment provider response',
            ]);

            $payment->update([
                'transaction_id' => $result['transaction_id'],
                'status' => $result['status'],
                'paid_at' => $result['status'] === 'succeeded' ? now() : null,
            ]);

            if ($result['status'] === 'succeeded') {
                $lockedOrder->update([
                    'payment_status' => 'paid',
                ]);
            }

            return $payment->refresh();
        });
    }
}
