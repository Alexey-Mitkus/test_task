<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use Sluggable;

    protected $connection = 'mysql_old';
	protected $table = 'media';
    
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'name',
        'type',
        'mediatable_type',
        'mediatable_id',
        'extension',
        'user_id',
        'thumbnails',
        'width',
        'height',
        'src',
        'mimes',
        'disk',
        'size',
        'folder',
        'storage',
        'storage_data',
        'slug'
    ];

    protected $casts = [
        'storage_data' => 'object'
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
		    $uid = Str::uuid();
		}while( Media::where('slug', $uid)->first() instanceof Media );
        return $uid;
    }

    public function mediatable()
    {
        return $this->morphTo();
    }

    public function user()
    {
		return $this->belongsTo('App\User', 'user_id');
    }
}
