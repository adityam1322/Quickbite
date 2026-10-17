<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::query()
            ->with([
                'user',
                'restaurant',
                'address',
                'deliveryAssignments.deliveryPartner.user',
            ])
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $orders,
        ]);
    }

    public function show(Order $order): JsonResponse
    {
        $order->load([
            'user',
            'restaurant',
            'address',
            'items',
            'deliveryAssignments.deliveryPartner.user',
            'payments',
            'statusHistories',
        ]);

        return response()->json([
            'data' => $order,
        ]);
    }
}