<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class KbTheme extends Model
{
    use Sluggable;
    
	protected $connection = 'mysql_old';
	protected $table = 'kb_themes';
    
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
		}while( KbTheme::where('slug', $uid)->first() instanceof KbTheme );
        return $uid;
    }

    public function posts()
    {
        return $this->belongsToMany('App\KbPost', 'kb_posts_has_themes', 'theme_id', 'post_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\KbTheme', 'parent_id', 'id');
    }

    public function childrens()
    {
        return $this->hasMany('App\KbTheme', 'parent_id')->with('childrens');
    }

    public function scopeActive($query)
    {
    	return $query->where('is_active', 1);
    }
}
