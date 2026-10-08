<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\MenuItemVariant;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderCalculationService;

class CartController extends Controller
{

    private function getUserCart()
    {
        return Cart::where('user_id', Auth::id())
            ->with([
                'restaurant',
                'items.menuItem',
                'items.menuItemVariant',
            ])
            ->firstOrFail();
    }
    public function show(Cart $cart)
    {

        $cart = $this->getUserCart();

        $cart->load([
            'restaurant',
            'items.menuItem',
            'items.menuItemVariant',
        ]);

        return response()->json([
            'cart' => $cart,
        ]);
    }

    public function addItem(AddCartItemRequest $request)
    {
        $cart = $this->getUserCart();

        $data = $request->validated();

        $menuItem = MenuItem::findOrFail(
            $data['menu_item_id']
        );

        // one restaurant per cart
        if (
            $cart->restaurant_id &&
            $cart->restaurant_id !== $menuItem->restaurant_id
        ) {
            if (! $data['confirm_restaurant_change'] ?? false) {
                return response()->json([
                    'message' => 'Your cart contains items from another restaurant.',
                    'confirmation_required' => true,
                    'confirmation_message' =>
                    'Adding this item will remove your existing cart items. Do you want to continue?',
                ], 409);
            }

            $cart->items()->delete();

            $cart->update([
                'restaurant_id' => $menuItem->restaurant_id,
            ]);
        }
        // if card is empty to set restaurant

        if (! $cart->restaurant_id) {
            $cart->update([
                'restaurant_id' => $menuItem->restaurant_id,
            ]);
        }

        $variant = MenuItemVariant::findOrFail(
            $data['menu_items_variants_id']
        );

        $cartItem = $cart->items()
            ->where('menu_item_id', $data['menu_item_id'])
            ->where(
                'menu_items_variants_id',
                $data['menu_items_variants_id']
            )
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];

            $cartItem->subtotal =
                $cartItem->unit_price * $cartItem->quantity;

            $cartItem->save();
        } else {
            $cartItem = $cart->items()->create([
                'menu_item_id' => $data['menu_item_id'],
                'menu_items_variants_id' => $data['menu_items_variants_id'],
                'quantity' => $data['quantity'],
                'unit_price' => $variant->price,
                'subtotal' => $variant->price * $data['quantity'],
            ]);
        }

        return response()->json([
            'message' => 'Item added to cart successfully.',
            'item' => $cartItem->load([
                'menuItem',
                'menuItemVariant',
            ]),
        ], 201);
    }

    public function updateItem(
        UpdateCartItemRequest $request,
        Cart $cart,
        CartItem $cartItem
    ) {
        $cart = $this->getUserCart();

        abort_unless(
            $cartItem->cart_id === $cart->id,
            403,
            'You cannot modify this cart item.'
        );

        $data = $request->validated();

        $subtotal = $cartItem->unit_price * $data['quantity'];

        $cartItem->update([
            'quantity' => $data['quantity'],
            'subtotal' => $subtotal,
        ]);

        return response()->json([
            'message' => 'Cart item updated successfully.',
            'item' => $cartItem->fresh([
                'menuItem',
                'menuItemVariant',
            ]),
        ]);
    }

    public function removeItem(
        CartItem $cartItem
    ) {
        $cart = $this->getUserCart();

        abort_unless(
            $cartItem->cart_id === $cart->id,
            403,
            'You cannot remove this cart item.'
        );

        $cartItem->delete();

        return response()->json([
            'message' => 'Cart item removed successfully.',
        ]);
    }

    public function clear(Cart $cart)
    {
        $cart->items()->delete();

        return response()->json([
            'message' => 'Cart cleared successfully.',
        ]);
    }

    public function calculateTotal(OrderCalculationService $orderCalculationService) {
        $cart = $this->getUserCart();

        $cart->load('items');

        $calculation = $orderCalculationService->calculate($cart);

        return response()->json([
            'calculation' => $calculation,
        ]);
    }
}
