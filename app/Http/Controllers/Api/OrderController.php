<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderCalculationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Coupon;

class OrderController extends Controller
{
    public function index()
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

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request, OrderCalculationService $orderCalculationService): JsonResponse
    {

        $idempotencyKey = $request->header('Idempotency-Key');

        if (! $idempotencyKey) {
            return response()->json([
                'message' => 'Idempotency-Key header is required.',
            ], 400);
        }

        $existingOrder = Order::where(
            'idempotency_key',
            $idempotencyKey
        )->first();

        if ($existingOrder) {
            return (new OrderResource(
                $existingOrder->load('items')
            ))->additional([
                'message' => 'Order already created.',
            ])->response();
        }



        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)
            ->with([
                'items.menuItem',
                'items.menuItemVariant',
            ])
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty.',
            ], 422);
        }

        foreach ($cart->items as $cartItem) {

            // Menu item availability
            if (! $cartItem->menuItem->is_available) {
                return response()->json([
                    'message' => $cartItem->menuItem->name
                        . ' is currently unavailable.',
                ], 422);
            }

            // Variant availability
            if (
                $cartItem->menuItemVariant &&
                ! $cartItem->menuItemVariant->is_avialable
            ) {
                return response()->json([
                    'message' => $cartItem->menuItemVariant->name
                        . ' variant is currently unavailable.',
                ], 422);
            }
        }

        //check restaurant hours
        $restaurant = $cart->restaurant;

        $now = now();

        $restaurantHour = $restaurant->hours()
            ->where('day_of_week', $now->dayOfWeek)
            ->first();

        if (! $restaurantHour) {
            return response()->json([
                'message' => 'Restaurant hours are not configured for today.',
            ], 422);
        }

        if ($restaurantHour->is_closed) {
            return response()->json([
                'message' => 'Restaurant is currently closed.',
            ], 422);
        }

        $currentTime = $now->format('H:i:s');

        if (
            $currentTime < $restaurantHour->open_time ||
            $currentTime > $restaurantHour->close_time
        ) {
            return response()->json([
                'message' => 'Restaurant is currently closed.',
            ], 422);
        }

        //check Address validate
        $address = $user->addresses()
            ->where('id', $request->address_id)
            ->first();

        if (! $address) {
            return response()->json([
                'message' => 'The selected address does not belong to you.',
            ], 403);
        }

        $isServiceable = $cart->restaurant
            ->serviceAreas()
            ->where('postal_code', $address->postal_code)
            ->where('is_active', true)
            ->exists();

        if (! $isServiceable) {
            return response()->json([
                'message' => 'This address is outside the restaurant delivery area.',
            ], 422);
        }

        //serviceability validate

        $isServiceable = $cart->restaurant
            ->serviceAreas()
            ->where('postal_code', $address->postal_code)
            ->where('is_active', true)
            ->exists();

        if (! $isServiceable) {
            return response()->json([
                'message' => 'This address is outside the restaurant delivery area.',
            ], 422);
        }

        //coupon rule Validate
        $coupon = null;
        $discountAmount = 0;

        if ($request->filled('coupon_code')) {

            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('is_active', true)
                ->first();

            if (! $coupon) {
                return response()->json([
                    'message' => 'Invalid or inactive coupon.',
                ], 422);
            }

            $now = now();

            if ($now->lt($coupon->start_at)) {
                return response()->json([
                    'message' => 'This coupon is not active yet.',
                ], 422);
            }

            if ($now->gt($coupon->expire_at)) {
                return response()->json([
                    'message' => 'This coupon has expired.',
                ], 422);
            }

            if ($cart->items->sum(
                fn($item) => $item->unit_price * $item->quantity
            ) < $coupon->min_order_amount) {
                return response()->json([
                    'message' => 'Minimum order amount for this coupon is ₹'
                        . $coupon->min_order_amount,
                ], 422);
            }

            if ($coupon->usages_limit <= 0) {
                return response()->json([
                    'message' => 'This coupon usage limit has been reached.',
                ], 422);
            }
        }

        //minimum order validate 
        $subtotal = $cart->items->sum(
            fn($item) => $item->unit_price * $item->quantity
        );

        if ($subtotal < $cart->restaurant->minimum_order_amount) {
            return response()->json([
                'message' => 'Minimum order amount is ₹'
                    . $cart->restaurant->minimum_order_amount,
                'minimum_order_amount' =>
                $cart->restaurant->minimum_order_amount,
                'current_subtotal' => $subtotal,
            ], 422);
        }


        //Calculate subtotal, tax, delivery fee, discount, and total on the server.

        $calculation = $orderCalculationService->calculate($cart);

        $order = DB::transaction(function () use (
            $request,
            $user,
            $cart,
            $calculation,
            $idempotencyKey
        ) {
            $order = Order::create([
                'user_id' => $user->id,
                'restaurant_id' => $cart->restaurant_id,
                'address_id' => $request->address_id,
                'order_number' => 'QB-' . strtoupper(Str::random(10)),
                'idempotency_key' => $idempotencyKey,

                'subtotal' => $calculation['subtotal'],
                'tax_amount' => $calculation['tax_amount'],
                'delivery_fee' => $calculation['delivery_fee'],
                'discount_amount' => $calculation['discount_amount'],
                'total_amount' => $calculation['total_amount'],

                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $request->notes,
            ]);


            //Create order/item snapshots within a transaction.

            foreach ($cart->items as $cartItem) {
                $order->items()->create([
                    'menu_item_id' => $cartItem->menu_item_id,
                    'menu_items_variants_id' =>
                    $cartItem->menu_items_variants_id,

                    'item_name' => $cartItem->menuItem->name,
                    'variant_name' =>
                    $cartItem->menuItemVariant?->name,

                    'unit_price' => $cartItem->unit_price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->subtotal,
                ]);
            }

            $cart->items()->delete();

            $cart->update([
                'status' => 'checked_out',
                'order_id' => $order->id,
            ]);

            return $order;
        });

        return (new OrderResource(
            $order->load('items')
        ))->additional([
            'message' => 'Order placed successfully.',
        ])->response()->setStatusCode(201);
    }

    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        return new OrderResource(
            $order->load('items')
        );
    }

    public function update(
        UpdateOrderRequest $request,
        Order $order
    ): JsonResponse {
        Gate::authorize('manage', $order);

        $order->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Order updated successfully.',
            'order' => $order->fresh(),
        ]);
    }
}
