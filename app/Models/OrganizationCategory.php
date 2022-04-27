<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class OrganizationCategory extends Model
{
    use Sluggable;

	protected $table = 'organizations_categories';
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
        }while( OrganizationCategory::where('slug', $uid)->first() instanceof OrganizationCategory );
        
        return $uid;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\OrganizationCategory', 'parent_id', 'id');
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\OrganizationCategory', 'parent_id')->with('childrens');
    }

    public function organizations()
    {
    	return $this->belongsToMany('App\Models\Organization', 'organizations_has_categories', 'category_id', 'organization_id');
    }
}