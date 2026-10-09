<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payments\RefundService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RefundOrderController extends Controller
{
    public function __invoke(
        Request $request,
        Order $order,
        RefundService $refundService
    ): JsonResponse {
        Gate::authorize('refund', $order);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $refund = $refundService->refund(
            $order,
            $validated['reason']
        );

        return response()->json([
            'message' => 'Refund request processed.',
            'data' => $refund,
        ], 200);
    }
}