<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderStatusTransitionService;
use Illuminate\Http\Request;

class MarkOrderReadyController extends Controller
{
    public function __invoke(
        Request $request,
        Order $order,
        OrderStatusTransitionService $transitions
    ) {
        $updated = $transitions->transition(
            $order,
            'ready_for_pickup',
            $request->user(),
            'markReady'
        );

        return response()->json([
            'message' => 'Order is ready for pickup.',
            'data' => $updated,
        ]);
    }
}