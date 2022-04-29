<?php

namespace App\Models\motivation;

use Illuminate\Database\Eloquent\Model;
use App\Models\motivation\MotivationBage;

class MotivationAchievement extends Model
{
    protected $table = 'motivation_achievements';
    
    protected $guarded = [
    	'id'
    ];
    
    protected $fillable = [
        'title',
        'bage_id',
        'type',
        'count',
        'time_limit',
        'image'

    ];

    public function bage() {
        return $this->belongsTo(MotivationBage::class);
    }
}
