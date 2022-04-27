<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class OrganizationType extends Model
{
    use Sluggable;

	protected $table = 'organizations_types';
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'name',
        'parent_id',
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
        }while( OrganizationType::where('slug', $uid)->first() instanceof OrganizationType );
        
        return $uid;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\OrganizationType', 'parent_id', 'id');
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\OrganizationType', 'parent_id')->with('childrens');
    }

    public function organizations()
    {
    	return $this->belongsToMany('App\Models\Organization', 'organizations_has_types', 'type_id', 'organization_id');
    }
}
