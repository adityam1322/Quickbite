<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'orders_status_histories';

    protected $fillable = [
        'order_id',
        'status',
        'notes',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('Order status history is append-only.'));
        static::deleting(fn () => throw new LogicException('Order status history is append-only.'));
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
