<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentWebhookEvent;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $secret = config('services.payment_webhook.secret');

        if (! is_string($secret) || $secret === '') {
            return response()->json([
                'message' => 'Webhook is not configured.',
            ], 500);
        }

        // Validate webhook timestamp and signature headers.
        $timestamp = $request->header('X-Webhook-Timestamp');
        $signature = $request->header('X-Webhook-Signature');

        if (
            ! is_string($timestamp) ||
            ! ctype_digit($timestamp) ||
            abs(time() - (int) $timestamp) > 300 ||
            ! is_string($signature) ||
            $signature === ''
        ) {
            return response()->json([
                'message' => 'Invalid webhook signature or timestamp.',
            ], 401);
        }

        // Verify the signature against the exact raw request body.
        $rawBody = $request->getContent();

        $expected = hash_hmac(
            'sha256',
            $timestamp . '.' . $rawBody,
            $secret
        );

        if (! hash_equals($expected, $signature)) {
            return response()->json([
                'message' => 'Invalid webhook signature.',
            ], 401);
        }

        // Validate the webhook payload.
        $validator = Validator::make($request->json()->all(), [
            'event_id' => ['required', 'string', 'max:255'],
            'event_type' => [
                'required',
                'in:payment.succeeded,payment.failed',
            ],
            'transaction_id' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid webhook payload.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        try {
            DB::transaction(function () use ($payload) {
                // Insert the event only once.
                DB::table('payment_webhook_events')->insert([
                    'event_id' => $payload['event_id'],
                    'event_type' => $payload['event_type'],
                    'status' => 'processing',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Lock the payment while applying the event.
                $payment = Payment::query()
                    ->where(
                        'transaction_id',
                        $payload['transaction_id']
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($payload['event_type'] === 'payment.succeeded') {
                    if ($payment->status !== 'succeeded') {
                        $payment->update([
                            'status' => 'succeeded',
                            'paid_at' => now(),
                        ]);

                        $payment->order()->update([
                            'payment_status' => 'paid',
                        ]);
                    }
                } elseif ($payment->status !== 'succeeded') {
                    $payment->update([
                        'status' => 'failed',
                    ]);
                }

                // Mark the event processed in the same transaction.
                DB::table('payment_webhook_events')
                    ->where('event_id', $payload['event_id'])
                    ->update([
                        'status' => 'processed',
                        'processed_at' => now(),
                        'updated_at' => now(),
                    ]);
            });
        } catch (UniqueConstraintViolationException $exception) {
            /*
             * A concurrent request with the same event_id may have
             * committed while this request was processing.
             *
             * Check the event ID specifically; do not treat every
             * unique constraint violation as a duplicate webhook.
             */
            $duplicateEvent = PaymentWebhookEvent::query()
                ->where('event_id', $payload['event_id'])
                ->exists();

            if ($duplicateEvent) {
                return response()->json([
                    'message' => 'Webhook event already received.',
                ], 200);
            }

            Log::error('Payment webhook unique constraint violation.', [
                'event_id' => $payload['event_id'],
            ]);

            return response()->json([
                'message' => 'Webhook processing failed.',
            ], 500);
        } catch (\Throwable $exception) {
            Log::error('Payment webhook processing failed.', [
                'event_id' => $payload['event_id'],
                'exception' => get_class($exception),
            ]);

            return response()->json([
                'message' => 'Webhook processing failed.',
            ], 500);
        }

        return response()->json([
            'message' => 'Webhook processed successfully.',
        ], 200);
    }
}
