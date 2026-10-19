<?php

namespace App\Mcp\Tools;

use App\Models\Order;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;

#[Name('get_order_status')]
#[Description('Get the status of an order belonging to the authenticated user.')]
class GetOrderStatus extends Tool
{
    public function handle(Request $request): Response
    {
        $orderId = $request->get('order_id');

        if (! is_numeric($orderId)) {
            return Response::text(json_encode([
                'success' => false,
                'message' => 'A valid order_id is required.',
            ], JSON_THROW_ON_ERROR));
        }

        $user = $request->user();

        if (! $user) {
            return Response::text(json_encode([
                'success' => false,
                'message' => 'Unauthorized.',
            ], JSON_THROW_ON_ERROR));
        }

        $order = Order::query()
            ->where('id', (int) $orderId)
            ->where('user_id', $user->id)
            ->select([
                'id',
                'user_id',
                'status',
                'created_at',
                'updated_at',
            ])
            ->first();

        if (! $order) {
            return Response::text(json_encode([
                'success' => false,
                'message' => 'Order not found.',
            ], JSON_THROW_ON_ERROR));
        }

        return Response::text(json_encode([
            'success' => true,
            'data' => [
                'order_id' => $order->id,
                'status' => $order->status,
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
