<?php

namespace App\Models\motivation;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MotivationTiket extends Model
{
    protected $table = 'motivation_tikets';
    
    protected $guarded = [
    	'id'
    ];
    
    protected $fillable = [
        'user_id',
        'achievment_id',
        'link',
        'message',
        'file',
        'date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}


