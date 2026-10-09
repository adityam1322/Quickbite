<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentReferenceService
{
    public function savePaymentReference(
        int $paymentId,
        string $transactionId,
        string $status
    ): Payment {
        if (! in_array($status, [
            'pending',
            'processing',
            'success',
            'failed',
        ], true)) {
            throw ValidationException::withMessages([
                'status' => 'Invalid payment status.',
            ]);
        }

        return DB::transaction(function () use (
            $paymentId,
            $transactionId,
            $status
        ) {
            $payment = Payment::whereKey($paymentId)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $payment->transaction_id !== null
                && $payment->transaction_id !== $transactionId
            ) {
                throw ValidationException::withMessages([
                    'transaction_id' => 'Payment reference mismatch.',
                ]);
            }

            $payment->transaction_id = $transactionId;
            $payment->status = $status;

            if ($status === 'success') {
                $payment->paid_at ??= now();
            }

            $payment->save();

            return $payment;
        });
    }

    public function saveRefundReference(
        int $refundId,
        string $refundReference,
        string $status
    ): Refund {
        if (! in_array($status, [
            'pending',
            'processing',
            'success',
            'failed',
        ], true)) {
            throw ValidationException::withMessages([
                'status' => 'Invalid refund status.',
            ]);
        }

        return DB::transaction(function () use (
            $refundId,
            $refundReference,
            $status
        ) {
            $refund = Refund::whereKey($refundId)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $refund->refund_refrence !== null
                && $refund->refund_refrence !== $refundReference
            ) {
                throw ValidationException::withMessages([
                    'refund_reference' => 'Refund reference mismatch.',
                ]);
            }

            $refund->refund_refrence = $refundReference;
            $refund->status = $status;

            if ($status === 'success') {
                $refund->refunded ??= now();
            }

            $refund->save();

            return $refund;
        });
    }
}
