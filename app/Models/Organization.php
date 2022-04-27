<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Organization extends Model
{
    use Sluggable;
    use SoftDeletes;
    use SearchableTrait;

	protected $table = 'organizations';
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'name',
        'abbreviation',
        'country_id',
        'city_id',
        'address',
        'raw_country',
        'raw_city',
        'raw_full_address',
        'lat',
        'lng',
        'description',
        'slug',
    ];

    protected $hidden = [];

    protected $casts = [
        'lat' => 'decimal:6',
        'lng' => 'decimal:6',
        'is_active' => 'boolean'
    ];
    
    protected $dates = [
        'deleted_at'
    ];

    protected $searchable = [

        'columns' => [
            'organizations.name' => 10,
            'organizations.abbreviation' => 8,
            'organizations.description' => 2
        ]
        
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
        }while( Organization::where('slug', $uid)->first() instanceof Organization );
        
        return $uid;
    }

    public function types()
    {
    	return $this->belongsToMany('App\Models\OrganizationType', 'organizations_has_types', 'organization_id', 'type_id');
    }

    public function categories()
    {
    	return $this->belongsToMany('App\Models\OrganizationCategory', 'organizations_has_categories', 'organization_id', 'category_id');
    }
}
