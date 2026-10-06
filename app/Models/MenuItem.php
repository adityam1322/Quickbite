<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_categories_id',
        'restaurant_id',
        'name',
        'slug',
        'discription',
        'is_vegetarian',
        'is_available',
    ];

    protected $casts = [
        'is_vegetarian' => 'boolean',
        'is_available' => 'boolean',
    ];

    /** @return BelongsTo<MenuCategorie, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategorie::class, 'menu_categories_id');
    }

    /** @return BelongsTo<Restaurant, $this> */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /** @return HasMany<MenuItemVariant, $this> */
    public function variants(): HasMany
    {
        return $this->hasMany(MenuItemVariant::class);
    }

    /** @return HasMany<MenuItemPrice, $this> */
    public function prices(): HasMany
    {
        return $this->hasMany(MenuItemPrice::class);
    }

    /** @return HasMany<MenuItemAvailabilitie, $this> */
    public function availabilities(): HasMany
    {
        return $this->hasMany(MenuItemAvailabilitie::class);
    }

    /** @return HasMany<MenuItemImage, $this> */
    public function images(): HasMany
    {
        return $this->hasMany(MenuItemImage::class);
    }

    /** @return HasMany<CartItem, $this> */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function isAvailableAt(?Carbon $at = null): bool
    {
        if (! $this->is_available) {
            return false;
        }

        $at ??= now();
        $time = $at->format('H:i:s');

        return $this->availabilities()
            ->where('date_of_week', (string) $at->dayOfWeek)
            ->where('is_available', true)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>=', $time)
            ->exists();
    }
}
