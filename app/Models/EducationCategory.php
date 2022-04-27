<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class EducationCategory extends Model
{
    use Sluggable;

	protected $table = 'education_categories';
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
        }while( EducationCategory::where('slug', $uid)->first() instanceof EducationCategory );
        
        return $uid;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function educations()
    {
    	return $this->belongsToMany('App\Models\Education', 'educations_has_categories', 'category_id', 'education_id');
    }
}