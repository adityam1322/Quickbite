<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $metrics = Cache::remember(
            'admin.dashboard.metrics',
            now()->addMinutes(5),
            function () {
                $from = now()->subDays(30)->startOfDay();
                $to = now()->endOfDay();

                $orders = Order::query()
                    ->whereBetween('created_at', [$from, $to]);

                return [
                    'total_orders' => (clone $orders)->count(),

                    'delivered_orders' => (clone $orders)
                        ->where('order_status', 'delivered')
                        ->count(),

                    'cancelled_orders' => (clone $orders)
                        ->where('order_status', 'cancelled')
                        ->count(),

                    'pending_orders' => (clone $orders)
                        ->whereNotIn('order_status', [
                            'delivered',
                            'cancelled',
                        ])
                        ->count(),

                    'total_revenue' => (clone $orders)
                        ->where('payment_status', 'paid')
                        ->sum('total_amount'),
                ];
            }
        );

        return response()->json([
            'data' => $metrics,
        ]);
    }
}