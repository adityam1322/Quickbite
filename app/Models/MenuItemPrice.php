<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class MenuItemPrice extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemPriceFactory> */
    use HasFactory;
    protected $fillable[
        'menu_item_id',
        'price',
        'currency',
        'effective_from',
        'effective_until',
    ];

    protected $casts[
        'price' => 'decimal:2',
        'effective_from' => 'date',
        'effective_until' => 'date',
    ];

    public function menuItem(): belongsTo{
        return $table->belongsTo(MenuItem::class);
    }


}
