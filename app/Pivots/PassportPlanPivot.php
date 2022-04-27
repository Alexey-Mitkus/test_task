<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PassportPlanPivot extends Pivot
{

    protected $fillable = [
        'name',
        'description',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d'
    ];

}