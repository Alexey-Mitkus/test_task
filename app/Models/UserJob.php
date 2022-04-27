<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class UserJob extends Model
{
    use Sluggable;

	protected $table = 'users_jobs';
    
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'organization_id',
        'raw_organization',
        'position',
        'start_at',
        'end_at',
        'user_id',
        'slug'
    ];

    protected $hidden = [];

    protected $casts = [
        'start_at' => 'datetime:Y-m-d',
        'end_at' => 'datetime:Y-m-d'
    ];

    protected $dates = [];
    protected $appends = [
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
        }while( UserJob::where('slug', $uid)->first() instanceof UserJob );
        
        return $uid;
    }
        
    public function tags()
    {
    	return $this->belongsToMany('App\Models\User', 'users_jobs_has_tags', 'job_id', 'user_id')->withPivot(['value']);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization', 'organization_id');
    }
}
