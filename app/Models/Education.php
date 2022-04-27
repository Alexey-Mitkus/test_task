<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Education extends Model
{
    use Sluggable;
    use SearchableTrait;

	protected $table = 'educations';
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'name',
        'country_id',
        'country_type',
        'city_id',
        'city_type',
        'description',
        'slug',
    ];

    protected $hidden = [];
    protected $casts = [];
    protected $dates = [];

    protected $searchable = [

        'columns' => [
            'educations.name' => 10
        ]
        
    ];

    public function sluggable(){
        return [
            'slug' => [
                'source' => 'generate_unique_uid'
            ]
        ];
    }

    public function scopeFilter(\Illuminate\Database\Eloquent\Builder $builder, \App\Filters\QueryFilter $filter)
    {
        return $filter->apply($builder);
    }
    
    public function getGenerateUniqueUidAttribute()
    {
        do{
            $uid = \Illuminate\Support\Str::uuid();
        }while( Education::where('slug', $uid)->first() instanceof Education );
        
        return $uid;
    }

    public function country()
    {
        return $this->morphTo();
    }

    public function city()
    {
        return $this->morphTo();
    }

    public function categories()
    {
    	return $this->belongsToMany('App\Models\EducationCategory', 'educations_has_categories', 'education_id', 'category_id');
    }
}