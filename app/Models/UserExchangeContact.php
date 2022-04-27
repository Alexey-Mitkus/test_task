<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class UserExchangeContact extends Model
{
    use Sluggable;

	protected $table = 'users_exchange_contacts';

    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'user_id',
        'target_id',
        'message',
        'status_id',
        'slug',
    ];

    protected $hidden = [];
    protected $casts = [];

    public function sluggable(){
        return [
            'slug' => [
                'source' => 'generate_unique_uid'
            ]
        ];
    }

    public function getGenerateUniqueUidAttribute()
    {
        do{
            $uid = \Illuminate\Support\Str::uuid();
        }while( UserExchangeContact::where('slug', $uid)->first() instanceof UserExchangeContact );
        
        return $uid;
    }

    public function requests()
    {
    	return $this->belongsToMany('App\Models\User', 'users_exchange_contacts_has_requests', 'exchange_id', 'user_id')->withPivot(['model_type', 'model_id']);
    }

    public function sharing()
    {
    	return $this->belongsToMany('App\Models\User', 'users_exchange_contacts_has_sharing', 'exchange_id', 'user_id')->withPivot(['model_type', 'model_id']);
    }
}