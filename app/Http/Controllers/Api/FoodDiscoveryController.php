<?php

namespace App\Http\Controllers\Api;

use App\Ai\Agents\FoodDiscoveryAgent;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class FoodDiscoveryController extends Controller
{
    public function discover(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        $startedAt = microtime(true);

        try {
            $agent = new FoodDiscoveryAgent();

            $response = $agent->prompt(
                $data['message']
            );

            $durationMs = (int) round(
                (microtime(true) - $startedAt) * 1000
            );

            $usage = $response->usage;

            Log::info('Food discovery AI request completed', [
                'provider' => config('ai.default'),
                'input_tokens' => $usage->inputTokens,
                'output_tokens' => $usage->outputTokens,
                'total_tokens' => $usage->totalTokens(),
                'duration_ms' => $durationMs,
            ]);

            return response()->json([
                'success' => true,
                'data' => $response,
            ]);
        } catch (Throwable $exception) {

            $durationMs = (int) round(
                (microtime(true) - $startedAt) * 1000
            );

            Log::error('Food discovery AI request failed', [
                'provider' => config('ai.default'),
                'duration_ms' => $durationMs,
                'error' => $exception->getMessage(),
                'exception' => get_class($exception),
                'trace' => $exception->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Food discovery service is temporarily unavailable.',
                'error' => app()->environment('local')
                    ? $exception->getMessage()
                    : null,
            ], 503);
        }
    }
}