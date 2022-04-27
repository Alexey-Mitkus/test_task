<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class City extends Model
{
    use Sluggable;

	protected $table = 'cities';
    protected $guarded = [
    	'id'
    ];
    
    protected $fillable = [
        'name',
        'country_id',
        'country_type',
        'region_id',
        'region_type',
        'lat',
        'lng',
        'is_active',
        'slug',
    ];

    protected $hidden = [
        'country_type',
        'region_type',
        'created_at',
        'updated_at',
        'is_active',
    ];
    
    protected $casts = [
        'lat' => 'decimal:6',
        'lng' => 'decimal:6',
        'is_active' => 'boolean'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'generate_unique_slug'
            ]
        ];
    }

    public function getGenerateUniqueSlugAttribute()
    {
        $collection = collect([
            $this->name
        ]);

        if( !empty($this->region) )
        {
            $collection->push($this->region->name);
        }

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
		}while( City::where('slug', $name)->first() instanceof City );

        return $name;
    }

    public function getFullAddressAttribute()
    {
        return $this->country->name . ', ' . ( !empty($this->region) ? $this->region->name . ', ' : '') . $this->name;
    }

    public function getFullAddressReverseAttribute()
    {
        return $this->name . ', ' . ( !empty($this->region) ? $this->region->name . ', ' : '') . $this->country->name;
    }

    public function country()
    {
        return $this->morphTo();
    }

    public function area()
    {
        return $this->morphTo();
    }

    public function region()
    {
        return $this->morphTo();
    }
    
    public function api()
    {
        return $this->morphMany('App\Models\ApiService', 'target');
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
