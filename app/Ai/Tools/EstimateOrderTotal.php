<?php

namespace App\Ai\Tools;

use App\Models\Cart;
use App\Services\OrderCalculationService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class EstimateOrderTotal implements Tool
{
    /**
     * Describe what this tool does.
     */
    public function description(): Stringable|string
    {
        return 'Calculate an estimated order total for the authenticated user current cart using the application pricing rules.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $user = $request->user();

        if (! $user) {
            return json_encode([
                'success' => false,
                'message' => 'Authentication is required.',
            ]);
        }

        /*
         * Get only the authenticated user's active cart.
         */
        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->with('items')
            ->latest('id')
            ->first();

        if (! $cart) {
            return json_encode([
                'success' => false,
                'message' => 'No active cart found.',
            ]);
        }

        /*
         * Use the existing deterministic pricing service.
         */
        $calculation = app(OrderCalculationService::class)
            ->calculate($cart);

        return json_encode([
            'success' => true,
            'cart_id' => $cart->id,
            'restaurant_id' => $cart->restaurant_id,
            'subtotal' => $calculation['subtotal'],
            'tax_amount' => $calculation['tax_amount'],
            'delivery_fee' => $calculation['delivery_fee'],
            'discount_amount' => $calculation['discount_amount'],
            'total_amount' => $calculation['total_amount'],
        ]);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}