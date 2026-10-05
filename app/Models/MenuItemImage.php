<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class MenuItemImage extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemImageFactory> */
    use HasFactory;
    protected $fillable[
        'menu_item_id',
        'image_url',
        'is_primary',
    ];

    protected $casts[
        'is_primary' => 'boolean',
    ];

    public function menuItem():belongsTo{
        return $table->belongsTo(MenuItem::class);
    }
}
