<?php

namespace App\Mcp\Tools;

use App\Models\Order;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get detailed information about an order belonging to the authenticated user.')]
class GetOrderDetails extends Tool
{
    public function handle(Request $request): Response
    {
        $orderId = $request->get('order_id');

        if (! is_numeric($orderId)) {
            return Response::text(json_encode([
                'success' => false,
                'message' => 'A valid order_id is required.',
            ]));
        }

        $user = $request->user();

        if (! $user) {
            return Response::text(json_encode([
                'success' => false,
                'message' => 'Unauthorized.',
            ]));
        }

        $order = Order::query()
            ->where('id', (int) $orderId)
            ->where('user_id', $user->id)
            ->with([
                'restaurant:id,name',
                'items',
            ])
            ->first();

        if (! $order) {
            return Response::text(json_encode([
                'success' => false,
                'message' => 'Order not found.',
            ]));
        }

        return Response::text(json_encode([
            'success' => true,
            'data' => [
                'order_id' => $order->id,
                'status' => $order->status,
                'restaurant' => $order->restaurant ? [
                    'id' => $order->restaurant->id,
                    'name' => $order->restaurant->name,
                ] : null,
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name ?? null,
                        'quantity' => $item->quantity ?? null,
                        'unit_price' => $item->unit_price ?? null,
                        'total' => $item->total ?? null,
                    ];
                })->values()->all(),
                'total_amount' => $order->total_amount ?? null,
                'created_at' => $order->created_at?->toISOString(),
                'updated_at' => $order->updated_at?->toISOString(),
            ],
        ], JSON_THROW_ON_ERROR));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'order_id' => $schema
                ->integer()
                ->description('The ID of the order.')
                ->required(),
        ];
    }
}