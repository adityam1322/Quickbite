<?php

namespace App\Mcp\Tools;

use App\Models\Order;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('List orders belonging to the authenticated user.')]
class ListMyOrders extends Tool
{
    public function handle(Request $request): Response
    {
        $user = $request->user();

        if (! $user) {
            return Response::text(json_encode([
                'success' => false,
                'message' => 'Unauthorized.',
            ]));
        }

        $status = $request->get('status');
        $limit = $request->get('limit', 10);

        $limit = min(max((int) $limit, 1), 20);

        $query = Order::query()
            ->where('user_id', $user->id)
            ->select([
                'id',
                'restaurant_id',
                'status',
                'total_amount',
                'created_at',
                'updated_at',
            ])
            ->with([
                'restaurant:id,name',
            ])
            ->latest('id');

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->limit($limit)->get();

        return Response::text(json_encode([
            'success' => true,
            'data' => [
                'count' => $orders->count(),
                'orders' => $orders->map(function (Order $order) {
                    return [
                        'order_id' => $order->id,
                        'status' => $order->status,
                        'total_amount' => $order->total_amount,
                        'restaurant' => $order->restaurant ? [
                            'id' => $order->restaurant->id,
                            'name' => $order->restaurant->name,
                        ] : null,
                        'created_at' => $order->created_at?->toISOString(),
                        'updated_at' => $order->updated_at?->toISOString(),
                    ];
                })->values()->all(),
            ],
        ], JSON_THROW_ON_ERROR));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'status' => $schema
                ->string()
                ->description('Optional order status filter.')
                ->nullable(),

            'limit' => $schema
                ->integer()
                ->description('Maximum number of orders to return. Maximum 20.')
                ->nullable(),
        ];
    }
}