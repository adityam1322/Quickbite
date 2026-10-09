<?php

namespace App\Services\Payments;

use App\Contracts\PaymentProviderInterface;
use Illuminate\Support\Str;
use InvalidArgumentException;

class FakePaymentProvider implements PaymentProviderInterface
{
    private static array $charges = [];
    private static array $refunds = [];

    public function charge(
        string $amount,
        string $currency,
        string $idempotencyKey
    ): array {
        if (isset(self::$charges[$idempotencyKey])) {
            return self::$charges[$idempotencyKey];
        }

        if (
            ! preg_match('/^\d+\.\d{2}$/', $amount) ||
            (float) $amount <= 0 ||
            ! preg_match('/^[A-Z]{3}$/', $currency)
        ) {
            throw new InvalidArgumentException('Invalid payment details.');
        }

        return self::$charges[$idempotencyKey] = [
            'transaction_id' => 'fake_pay_' . Str::uuid(),
            'status' => 'succeeded',
            'amount' => $amount,
            'currency' => $currency,
        ];
    }

    public function refund(
        string $transactionId,
        string $amount,
        string $idempotencyKey
    ): array {
        if (trim($idempotencyKey) === '') {
            throw new InvalidArgumentException(
                'Refund idempotency key is required.'
            );
        }


        if (isset(self::$refunds[$idempotencyKey])) {
            return self::$refunds[$idempotencyKey];
        }

        if (
            $transactionId === '' ||
            ! preg_match('/^\d+\.\d{2}$/', $amount) ||
            (float) $amount <= 0
        ) {
            throw new InvalidArgumentException('Invalid refund details.');
        }

        return self::$refunds[$idempotencyKey] = [
            'refund_reference' => 'fake_refund_' . Str::uuid(),
            'transaction_id' => $transactionId,
            'status' => 'succeeded',
            'amount' => $amount,
        ];
    }
}
