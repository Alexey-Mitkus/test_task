<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class UserEducation extends Model
{
    use Sluggable;

	protected $table = 'users_educations';
    
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'user_id',
        'education_category_id',
        'education_id',
        'raw_education',
        'position',
        'course_name',
        'course_organization',
        'course_link',
        'start_at',
        'end_at',
        'slug'
    ];

    protected $hidden = [];

    protected $casts = [
        'start_at' => 'datetime:Y-m-d',
        'end_at' => 'datetime:Y-m-d'
    ];

    protected $dates = [];
    protected $appends = [];

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
        }while( UserEducation::where('slug', $uid)->first() instanceof UserEducation );
        
        return $uid;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function university()
    {
        return $this->belongsTo('App\Models\Education', 'education_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\EducationCategory', 'education_category_id');
    }
}
