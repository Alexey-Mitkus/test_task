<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class UserField extends Model
{
    use Sluggable;

	protected $table = 'users_fileds';
    
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'name',
        'type_id',
        'options',
        'group_id',
        'is_active',
        'slug'
    ];

    protected $hidden = [];

    protected $casts = [
        'is_active' => 'boolean',
        'group_id' => 'integer',
        'type_id' => 'integer',
        'options' => 'array',
    ];

    protected $dates = [];
    protected $appends = [];

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
        }while( UserField::where('slug', $uid)->first() instanceof UserField );
        
        return $uid;
    }

    public function scopeActive($query)
    {
    	return $query->where('is_active', 1);
    }
    
    public function users()
    {
    	return $this->belongsToMany('App\Models\User', 'users_fileds_has_users', 'field_id', 'user_id')->using(\App\Pivots\UserFieldServicePivot::class)->withPivot(['value', 'points', 'is_show']);
    }

}
