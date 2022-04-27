<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserManagmentRole extends Model
{
    use Sluggable;
    use SoftDeletes;

	protected $table = 'users_managments_roles';
    
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'slug',
    ];

    protected $hidden = [];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $dates = [
        'deleted_at'
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
        }while( UserManagmentRole::where('slug', $uid)->first() instanceof UserManagmentRole );
        
        return $uid;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function managments()
    {
    	return $this->hasMany('App\Models\UserManagment', 'role_id');
    }
}
