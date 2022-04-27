<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Region extends Model
{
    use Sluggable;
    
	protected $table = 'cities_regions';
    protected $guarded = [
    	'id'
    ];
    
    protected $fillable = [
        'name',
        'country_id',
        'country_type',
        'lat',
        'lng',
        'is_active',
        'slug',
    ];

    protected $hidden = [
        'country_type',
        'created_at',
        'updated_at',
        'is_active',
    ];

    protected $casts = [
        'lat' => 'decimal:6',
        'lng' => 'decimal:6',
        'is_active' => 'boolean'
    ];

    public function sluggable(){
        return [
            'slug' => [
                // 'source' => 'name'
                'source' => 'generate_unique_slug'
            ]
        ];
    }

    public function getGenerateUniqueSlugAttribute()
    {
        $collection = collect([
            $this->name
        ]);

        if( !empty($this->country) )
        {
            $collection->push($this->country->name);
        }

        $i=1;
		do{
            if( $collection->count() >= $i )
            {
                $list = $collection->slice(0, $i);
                $list = $list->all();
                $name = (new \Cocur\Slugify\Slugify(['rulesets' => ['default', 'russian']]))->slugify(implode(' ', $list));
            }else{
                $name = (new \Cocur\Slugify\Slugify(['rulesets' => ['default', 'russian']]))->slugify((string) $collection->implode(' ') . ' ' . Str::random(8));
            }
            $i++;
		}while( Region::where('slug', $name)->first() instanceof Region );

        return $name;
    }
    
    public function getFullAddressReverseAttribute()
    {
        return $this->country->name . ', ' . $this->name;
    }

    public function getFullAddressAttribute()
    {
        return $this->name . ', ' . $this->country->name;
    }

    public function api()
    {
        return $this->morphMany('App\Models\ApiService', 'target');
    }
    
    public function country()
    {
		return $this->morphTo();
    }

    public function cities()
    {
        return $this->morphMany('App\Models\City', 'region');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    
}