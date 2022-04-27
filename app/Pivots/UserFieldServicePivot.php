<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserFieldServicePivot extends Pivot
{
    protected $fillable = [
        'value',
        'is_show',
        'points'
    ];

    protected $casts = [
        // 'value' => 'string',
        'is_show' => 'integer',
        'points' => 'integer'
    ];

}