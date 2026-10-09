<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use App\Services\PaymentReferenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentReferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_transaction_reference_is_saved(): void
    {
        $order = Order::factory()->create();

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'online',
            'amount' => '500.00',
            'currency' => 'INR',
            'status' => 'pending',
        ]);

        app(PaymentReferenceService::class)->savePaymentReference(
            $payment->id,
            'txn_test_123',
            'success'
        );

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'transaction_id' => 'txn_test_123',
            'status' => 'success',
        ]);
    }

    public function test_refund_reference_is_saved(): void
    {
        $order = Order::factory()->create();

        $payment = Payment::create([
    'order_id' => $order->id,
    'payment_method' => 'online',
    'amount' => '500.00',
    'currency' => 'INR',
    'status' => 'pending',
    'paid_at' => now(),
]);

        $refund = Refund::create([
            'payment_id' => $payment->id,
            'amount' => '100.00',
            'status' => 'pending',
            'reson' => 'Customer requested refund',
'refunded' => now(),
        ]);

        app(PaymentReferenceService::class)->saveRefundReference(
            $refund->id,
            'ref_test_123',
            'success'
        );

        $this->assertDatabaseHas('refunds', [
            'id' => $refund->id,
            'refund_refrence' => 'ref_test_123',
            'status' => 'success',
        ]);
    }
}