<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable[
        'user_id',
        'restaurant_id',
        'address_id',
        'order_number',
        'subtotal',
        'delivery_fee',
        'total_amount',
        'payment_status',
        'order_status',
        'notes',
    ];

    protected $casts[
        'subtotal' => 'decimal',
        'delivery_fee' => 'decimal',
        'total_amount' => 'decimal',
    ];

    public function user():belongsTo{
        return $table->belongsTo(User::class);
    }

    public function restaurant():belongsTo{
        return $table->belongsTo(Restaurant::class);
    }

    public function address():belongsTo{
        return $table->belongsTo(Address::class);
    }
}
