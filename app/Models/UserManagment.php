<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class UserManagment extends Model
{
    use Sluggable;

	protected $table = 'users_managments';
    
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'start_at',
        'end_at',
        'getting',
        'share',
        'user_id',
        'role_id',
        'slug'
    ];

    protected $hidden = [];

    protected $casts = [
        'start_at' => 'datetime:Y-m-d',
        'end_at' => 'datetime:Y-m-d'
    ];

    protected $dates = [];
    protected $appends = [
        'experience'
    ];

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
        }while( UserManagment::where('slug', $uid)->first() instanceof UserManagment );
        
        return $uid;
    }

    public function getExperienceAttribute()
    {
        return \Carbon\Carbon::parse($this->start_at)->diffInYears(\Carbon\Carbon::parse($this->end_ar));
    }

    public function methodologies()
    {
    	return $this->belongsToMany('App\Models\User', 'users_managments_has_methodologies', 'managment_id', 'user_id')->withPivot(['value']);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\UserManagmentRole', 'role_id');
    }
}
