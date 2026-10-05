<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;
    protected $fillable[
        'order_id',
        'restaurant_id',
        'status',
    ];

    public function order():belongsTo{
        return $table->belongsTo(Order::class);
    }

    public function restaurant():belongsTo{
        return $table->belongsTo(Restaurant::class);
    }

    public function cartItem():hasMany{
        return $table->hasMany(CartItem::class);
    }
    
}
