<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderStatusTransitionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcceptOrderController extends Controller
{
    public function __invoke(
        Request $request,
        Order $order,
        OrderStatusTransitionService $transitions
    ): JsonResponse {
        $updatedOrder = $transitions->transition(
            $order,
            'accepted',
            $request->user(),
            'accept'
        );

        return response()->json([
            'message' => 'Order accepted successfully.',
            'data' => $updatedOrder,
        ]);
    }
}