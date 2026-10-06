<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItemVariant extends Model
{
    use HasFactory;

    protected $table = 'menu_items_variants';

    protected $fillable = [
        'menu_item_id',
        'name',
        'price',
        'is_avialable',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_avialable' => 'boolean',
    ];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'menu_items_variants_id');
    }
}
