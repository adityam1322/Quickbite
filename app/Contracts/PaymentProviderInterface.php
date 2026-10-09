<?php

namespace App\Contracts;

interface PaymentProviderInterface
{
    public function charge(
        string $amount,
        string $currency,
        string $idempotencyKey
    ): array;

    public function refund(
        string $transactionId,
        string $amount,
        string $idempotencyKey
    ): array;
}