<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $user = request()->user();

        if ($user->hasRole('administrator')) {
            $orders = Order::query()
                ->latest()
                ->get();
        } else {
            $orders = Order::query()
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return response()->json([
            'orders' => $orders,
        ]);
    }

   public function show(Order $order): JsonResponse
{
    Gate::authorize('view', $order);

    return response()->json([
        'order' => $order,
    ]);
}

    public function update(
        UpdateOrderRequest $request,
        Order $order
    ): JsonResponse {
        $order->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Order updated successfully.',
            'order' => $order->fresh(),
        ]);
    }
}