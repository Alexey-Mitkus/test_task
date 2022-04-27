<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventLogCategory extends Model
{
    use Sluggable;
    use SoftDeletes;

	protected $table = 'events_log_categories';
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
    protected $dates = [
        'deleted_at'
    ];
    
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
        }while( EventLogCategory::where('slug', $uid)->first() instanceof EventLogCategory );
        
        return $uid;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function events()
    {
        return $this->hasMany('App\Models\EventLog', 'category_id');
    }
}
