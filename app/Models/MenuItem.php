<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class MenuItem extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemFactory> */
    use HasFactory;
    protected $fillable[
        'menu_categories_id',
        'restaurant_id',
        'name',
        'slug',
        'discription',
        'is_vegetarian',
        'is_available',

    ];

    protected $casts[
        'is_vegetarian' => 'boolean',
        'is_available' => 'boolean',
        
    ];

    public function Categorie():belongsTo{
        return $this->belongsTo(MenuCategorie::class);
    }

    public function Restaurant():belongsTo{
        return $this->belongsTo(Restaurant::class);
    }

    public function menuItemVariants():hasMany{
        return $this->hasMany(MenuItemVariant::class);
    }

    public function menuItemPrice():hasMany{
        return $this->hasMany(MenuItemPrice::class);
    }

    public function menuItemAvailabilitie():hasMany{
        return $this->hasMany(MenuItemAvailabilitie::class);
    }

    public function menuItemImage():hasMany{
        return $this->hasMany(MenuItemImage::class);
    }

    public function cartItem():hasMany{
        return $this->hasMany(CartItem::class);
    }

}
