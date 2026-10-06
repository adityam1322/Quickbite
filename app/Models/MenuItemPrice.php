<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItemPrice extends Model
{
    use HasFactory;

    protected $table = 'menu_items_price';

    protected $fillable = [
        'menu_item_id',
        'price',
        'currency',
        'effective_from',
        'effective_until',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'effective_from' => 'date',
        'effective_until' => 'date',
    ];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}
