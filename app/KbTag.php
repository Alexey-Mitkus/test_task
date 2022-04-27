<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class KbTag extends Model
{
    use Sluggable;
    
    protected $connection = 'mysql_old';
	protected $table = 'kb_tags';
    
    protected $guarded = [
    	'id'
    ];
    
    protected $fillable = [
        'name',
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
		}while( KbTag::where('slug', $uid)->first() instanceof KbTag );
        return $uid;
    }

    public function posts()
    {
    	return $this->belongsToMany('App\KbPost', 'kb_posts_has_tags', 'tag_id', 'post_id');
    }

    public function scopeActive($query)
    {
    	return $query->where('is_active', 1);
    }
}
