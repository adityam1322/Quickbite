<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class MenuItemAvailabilitie extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemAvailabilitieFactory> */
    use HasFactory;
    protected $fillable[
        'menu_item_id',
        'date_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts[
        'start_time' => 'time',
        'end_time' => 'time',
        'is_available' => 'boolean',
    ];

    public function menuItem():belongsTo{
        return $table->belongsTo(MenuItem::class);
    }
}
