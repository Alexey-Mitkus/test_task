<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PassportInterestPivot extends Pivot
{

    protected $fillable = [
        'name',
        'role'
    ];

    protected $casts = [
    ];

}