<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderStatusTransitionService;
use Illuminate\Http\Request;

class PickupOrderController extends Controller
{
    public function __invoke(
        Request $request,
        Order $order,
        OrderStatusTransitionService $transitions
    ) {
        $updated = $transitions->transition(
            $order,
            'picked_up',
            $request->user(),
            'pickup'
        );

        return response()->json([
            'message' => 'Order picked up.',
            'data' => $updated,
        ]);
    }
}