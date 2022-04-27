<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Traits\ImageRepositoryTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class KbPost extends Model
{
    use Sluggable;
    use SoftDeletes;
    use SearchableTrait;
    use ImageRepositoryTrait;

    protected $connection = 'mysql_old';
	protected $table = 'kb_posts';
    
    protected $guarded = [
    	'id'
    ];
    
    protected $fillable = [
        'name',
        'description',
        'lang',
        'status_id',
        'user_id',
        'views',
        'is_active',
        'slug',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $dates = [
    	'deleted_at'
    ];

    const CONST_STATUS_MODERATION = 100;
    const CONST_STATUS_PUBLISHED = 200;

	protected $statusTranslateLabels = [
        self::CONST_STATUS_MODERATION => 'Ожидает модерации',
        self::CONST_STATUS_PUBLISHED => 'Опубликован',
	];

	protected $statusLabels = [
        self::CONST_STATUS_MODERATION => 'moderation',
        self::CONST_STATUS_PUBLISHED => 'published',
	];

    protected $searchable = [

        'columns' => [
            'kb_posts.name' => 10,
            'kb_posts.description' => 9,
            'tags.name' => 8,
        ],

        'joins' => [
            'kb_posts_has_tags' => ['kb_posts.id', 'kb_posts_has_tags.post_id'],
            'kb_tags as tags' => ['kb_posts_has_tags.tag_id', 'tags.id']
        ]
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
		}while( KbPost::where('slug', $uid)->first() instanceof KbPost );
        return $uid;
    }

    public function scopeFilter(\Illuminate\Database\Eloquent\Builder $builder, \App\Filters\QueryFilter $filter)
    {
        return $filter->apply($builder);
    }


    public function getStatusAttribute()
    {
        return $this->statusLabels[$this->attributes['status_id']];
    }

    public function getStatusHumanAttribute()
    {
        return $this->statusTranslateLabels[$this->attributes['status_id']];
    }
    
    public function getTitleAttribute()
    {
        return $this->name;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['name'] = $value;
    }
    
    public function getFileAttribute()
    {
        return $this->media()->where('type', 'file')->first();
    }

    public function getFileUrlAttribute()
    {
        if( empty($this->file) )
        {
            return null;
        }
        return \Illuminate\Support\Facades\Storage::disk($this->file->disk)->url($this->file->folder . $this->file->src);
    }

    public function getLinkAttribute()
    {
        return $this->media()->where('type', 'link')->first();
    }

    public function getImageAttribute()
    {
        return $this->media()->where('type', 'image')->first();
    }

    public function getImageUrlAttribute()
    {
        $pic = $this->pictures('398x243', $justCheck = true, $collection = 'poster');
        if( $pic )
        {
            return $pic->src;
        }
    }

    public function scopeActive($query)
    {
    	return $query->where('is_active', 1);
    }

    public function themes()
    {
    	return $this->belongsToMany('App\KbTheme', 'kb_posts_has_themes', 'post_id', 'theme_id');
    }

    public function formats()
    {
    	return $this->belongsToMany('App\KbFormat', 'kb_posts_has_formats', 'post_id', 'format_id');
    }

    public function owner()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function likes()
    {
    	return $this->belongsToMany('App\User', 'kb_posts_has_likes', 'post_id', 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\KbTag', 'kb_posts_has_tags', 'post_id', 'tag_id');
    }

    public function subthemes()
    {
        return $this->themes()->whereNotNull('parent_id');
    }

    public function links()
    {
        return $this->media()->where('type', 'link');
    }

    public function files()
    {
        return $this->media()->where('type', 'file');
    }

    public function images()
    {
        return $this->media()->where('type', 'image');
    }

    public function media()
    {
        return $this->morphMany('App\Media', 'mediatable');
    }
    
    public function getPicturesArrayAttribute()
    {
        return config('knowledgebase.posters.sizes');
    }
}
