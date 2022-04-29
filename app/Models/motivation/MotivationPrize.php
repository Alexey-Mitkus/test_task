<?php

namespace App\Models\motivation;

use Illuminate\Database\Eloquent\Model;

class MotivationPrize extends Model
{
    protected $table = 'motivation_prizes';
    
    protected $guarded = [
    	'id'
    ];
    
    protected $fillable = [
        'user_id',
        'bages_count',
        'status',
        'prize_get',
        'date',
    ];
}