<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentWebhookEvent;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    private string $secret = 'test-secret';

    private string $webhookUrl = '/api/v1/payment/webhook';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.payment_webhook.secret' => 'test-secret',
        ]);
    }

    private function sendSignedWebhook(
        array $payload,
        ?string $timestamp = null
    ) {
        $timestamp ??= (string) time();

        $body = json_encode($payload, JSON_THROW_ON_ERROR);

        $secret = (string) config('services.payment_webhook.secret');

        $signature = hash_hmac(
            'sha256',
            $timestamp . '.' . $body,
            $secret
        );

        $response = $this->call(
            'POST',
            $this->webhookUrl,
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_X_WEBHOOK_TIMESTAMP' => $timestamp,
                'HTTP_X_WEBHOOK_SIGNATURE' => $signature,
            ],
            $body
        );

       

        return $response;
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        $payload = [
            'event_id' => 'evt_invalid_001',
            'event_type' => 'payment.succeeded',
            'transaction_id' => 'fake_txn_001',
        ];

        $body = json_encode($payload, JSON_THROW_ON_ERROR);
        $timestamp = (string) time();

        $response = $this->call(
            'POST',
            $this->webhookUrl,
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_X_WEBHOOK_TIMESTAMP' => $timestamp,
                'HTTP_X_WEBHOOK_SIGNATURE' => 'invalid-signature',
            ],
            $body
        );

        $response->assertUnauthorized();

        $this->assertDatabaseMissing('payment_webhook_events', [
            'event_id' => 'evt_invalid_001',
        ]);
    }

    public function test_webhook_rejects_expired_timestamp(): void
    {
        $payload = [
            'event_id' => 'evt_expired_001',
            'event_type' => 'payment.succeeded',
            'transaction_id' => 'fake_txn_002',
        ];

        $response = $this->sendSignedWebhook(
            $payload,
            (string) (time() - 600)
        );

        $response->assertUnauthorized();

        $this->assertDatabaseMissing('payment_webhook_events', [
            'event_id' => 'evt_expired_001',
        ]);
    }

    public function test_webhook_event_id_must_be_unique(): void
    {
        PaymentWebhookEvent::factory()->create([
            'event_id' => 'evt_unique_001',
        ]);

        $this->expectException(QueryException::class);

        PaymentWebhookEvent::factory()->create([
            'event_id' => 'evt_unique_001',
        ]);
    }

    public function test_valid_signed_webhook_marks_payment_as_succeeded(): void
    {
        $order = Order::factory()->create([
            'payment_status' => 'pending',
        ]);

        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'transaction_id' => 'fake_txn_success_001',
            'status' => 'pending',
        ]);

        $response = $this->sendSignedWebhook([
            'event_id' => 'evt_success_001',
            'event_type' => 'payment.succeeded',
            'transaction_id' => $payment->transaction_id,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'succeeded',
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('payment_webhook_events', [
            'event_id' => 'evt_success_001',
            'status' => 'processed',
        ]);
    }

    public function test_duplicate_webhook_request_does_not_create_duplicate_event(): void
    {
        $order = Order::factory()->create([
            'payment_status' => 'pending',
        ]);

        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'transaction_id' => 'fake_txn_duplicate_001',
            'status' => 'pending',
        ]);

        $payload = [
            'event_id' => 'evt_duplicate_001',
            'event_type' => 'payment.succeeded',
            'transaction_id' => $payment->transaction_id,
        ];

        $this->sendSignedWebhook($payload)->assertOk();

        $this->sendSignedWebhook($payload)->assertOk();

        $this->assertDatabaseCount('payment_webhook_events', 1);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'succeeded',
        ]);

        $this->assertDatabaseHas('payment_webhook_events', [
            'event_id' => 'evt_duplicate_001',
            'status' => 'processed',
        ]);
    }
}
