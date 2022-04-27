<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Passport extends Model
{
    use Sluggable;

	protected $table = 'passports';
    
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'name',
        'organization_id',
        'raw_organization',
        'category_id',
        'user_id',
        'prerequisite',
        'description',
        'vision',
        'start_at',
        'end_at',
        'is_complete',
        'step',
        'status',
        'slug',
    ];

    protected $hidden = [];
    
    protected $casts = [
        'is_complete' => 'boolean',
        'start_at' => 'datetime:Y-m-d',
        'end_at' => 'datetime:Y-m-d',
    ];

    protected $dates = [];

    public function sluggable()
    {
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
        }while( Passport::where('slug', $uid)->first() instanceof Passport );
        
        return $uid;
    }

    public function getNameAttribute($value)
    {
        return ( !empty($value) ? $value : 'Без названия' );
    }

    public function scopeComplete($query)
    {
        return $query->where('is_complete', 1);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\PassportCategory', 'category_id');
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization', 'organization_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function directors()
    {
    	return $this->belongsToMany('App\Models\User', 'passports_has_directors', 'passport_id', 'user_id')->withPivot(['name']);
    }

    public function teams()
    {
    	return $this->belongsToMany('App\Models\User', 'passports_has_teams', 'passport_id', 'user_id')->withPivot(['name']);
    }

    public function interests()
    {
    	return $this->belongsToMany('App\Models\User', 'passports_has_interests', 'passport_id', 'user_id')->using(\App\Pivots\PassportInterestPivot::class)->withPivot(['name', 'role']);
    }

    public function objectives()
    {
    	return $this->belongsToMany('App\Models\User', 'passports_has_objectives', 'passport_id', 'user_id')->withPivot(['name']);
    }

    public function results()
    {
    	return $this->belongsToMany('App\Models\User', 'passports_has_results', 'passport_id', 'user_id')->withPivot(['name']);
    }

    public function metrics()
    {
    	return $this->belongsToMany('App\Models\User', 'passports_has_metrics', 'passport_id', 'user_id')->withPivot(['name', 'value', 'before', 'after']);
    }

    public function resources()
    {
    	return $this->belongsToMany('App\Models\User', 'passports_has_resources', 'passport_id', 'user_id')->withPivot(['name', 'value']);
    }

    public function budgets()
    {
    	return $this->belongsToMany('App\Models\User', 'passports_has_budgets', 'passport_id', 'user_id')->withPivot(['name', 'value', 'type_id']);
    }

    public function risks()
    {
    	return $this->belongsToMany('App\Models\User', 'passports_has_risks', 'passport_id', 'user_id')->withPivot(['name']);
    }

    public function plans()
    {
    	return $this->belongsToMany('App\Models\User', 'passports_has_plans', 'passport_id', 'user_id')->using(\App\Pivots\PassportPlanPivot::class)->withPivot(['name', 'description', 'date']);
    }
}
