<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderStatusTransitionService;
use Illuminate\Http\Request;

class CancelOrderController extends Controller
{
    public function __invoke(
        Request $request,
        Order $order,
        OrderStatusTransitionService $transitions
    ) {
        $updated = $transitions->transition(
            $order,
            'cancelled',
            $request->user(),
            'cancel'
        );

        return response()->json([
            'message' => 'Order cancelled.',
            'data' => $updated,
        ]);
    }
}