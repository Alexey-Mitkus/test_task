<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class KbFormat extends Model
{
    use Sluggable;
    
    protected $connection = 'mysql_old';
	protected $table = 'kb_formats';
    
    protected $guarded = [
    	'id'
    ];
    
    protected $fillable = [
        'name',
        'parent_id',
        'user_id',
        'is_active',
        'slug',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

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
            // $uid = Str::uuid();
            $uid = $this->name . '-' . Str::random(6);
		}while( KbFormat::where('slug', $uid)->first() instanceof KbFormat );
        return $uid;
    }

    public function parent()
    {
        return $this->belongsTo('App\KbFormat', 'parent_id', 'id');
    }

    public function childrens()
    {
        return $this->hasMany('App\KbFormat', 'parent_id')->with('childrens');
    }

    public function posts()
    {
    	return $this->belongsToMany('App\KbPost', 'kb_posts_has_formats', 'format_id', 'post_id');
    }

    public function scopeActive($query)
    {
    	return $query->where('is_active', 1);
    }
}
