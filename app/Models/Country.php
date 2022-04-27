<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use Sluggable;

	protected $table = 'countries';
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'name',
        'code',
        'is_active',
        'slug',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function sluggable(){
        return [
            'slug' => [
                'source' => 'generate_unique_slug'
            ]
        ];
    }

    public function getGenerateUniqueSlugAttribute()
    {
		do{
            $uid = (new \Cocur\Slugify\Slugify(['rulesets' => ['default', 'russian']]))->slugify($this->name);
		}while( Country::where('slug', $uid)->first() instanceof Country );
        return $uid;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function regions()
    {
        return $this->morphMany('App\Models\Region', 'country');
    }

    public function cities()
    {
        return $this->morphMany('App\Models\City', 'country');
    }

    public function api()
    {
        return $this->morphMany('App\Models\ApiService', 'target');
    }
}
