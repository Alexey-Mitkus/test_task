<?php

namespace App\Models\motivation;

use Illuminate\Database\Eloquent\Model;
use App\Models\motivation\MotivationAchievement;

class MotivationBage extends Model
{
    
	protected $table = 'motivation_bages';
    
    protected $guarded = [
    	'id'
    ];
    
    protected $fillable = [
        'title',
        'count'
    ];


    public function motivationAchievement() {
        return $this->hasMany(MotivationAchievement::class);
    }
}
