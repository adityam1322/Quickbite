<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McpToolLog extends Model
{
    protected $fillable = [
        'user_id',
        'tool_name',
        'duration_ms',
        'outcome',
    ];

    protected $casts = [
        'duration_ms' => 'integer',
    ];
}