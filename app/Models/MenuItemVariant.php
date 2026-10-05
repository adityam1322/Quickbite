<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItemVariant extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemVariantFactory> */
    use HasFactory;
    protected $fillable[
        'menu_item_id',
        'name',
        'price',
        'is_avialable',

    ];

    protected $casts[
        'price' => 'decimal',
        'is_avialable' => 'boolean',
    ];

    public function MenuItem(): belongsTo{
        return $table->belongsTo(MenuItem::class);
    }

    public function cartItem(): hasMany{
        return $table->hasMany(CartItem::class);
    }

}
