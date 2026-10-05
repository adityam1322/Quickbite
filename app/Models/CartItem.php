<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    /** @use HasFactory<\Database\Factories\CartItemFactory> */
    use HasFactory;
    protected $fillable[
        'cart_id',
        'menu_item_id',
        'menu_items_variants_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts[
        'quantity' => 'unsignedInteger',
        'unit_price' => 'unit_price',
        'subtotal' => 'subtotal',
    ];

    public function cart():belongsTo{
        return $table->belongsTo(Cart::class);
    }

    public function menuItem():belongsTo{
        return $table->belongsTo(MenuItem::class);
    }
    public function menuItemVariant():belongsTo{
        return $table->belongsTo(MenuItemVariant::class);
    }
}
