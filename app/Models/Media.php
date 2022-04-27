<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use Sluggable;

	protected $table = 'media';
    
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'name',
        'parent_id',
        'type',
        'thumbnails',
        'ordering',
        'mediatable_type',
        'mediatable_id',
        'size',
        'extension',
        'collection',
        'tag',
        'width',
        'height',
        'user_id',
        'src',
        'mimes',
        'disk',
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
    
    public function getWebpAttribute()
    {
        return ( Storage::disk($this->disk)->exists($this->folder . pathinfo($this->src, PATHINFO_FILENAME) . '.webp') ? pathinfo($this->src, PATHINFO_FILENAME) . '.webp' : null );
    }

    public function getCloudAttribute()
    {
        return $this->storage_data;
    }

    public function getUrlAttribute()
    {
        $picture = $this;
        $newArray = [
            'src' => Storage::disk($picture->disk)->url($picture->folder . $picture->src),
            'thumbnails' => []
        ];
        $thumbnails = $picture->thumbnails;
        if( !empty($thumbnails) )
        {
            foreach($thumbnails as $pkey => $pick):
                $newArray['thumbnails'][$pick->tag] = (object) [
                    'src' => Storage::disk($pick->disk)->url($pick->folder . $pick->src),
                    'retina' => Storage::disk($pick->retina->disk)->url($pick->retina->folder . $pick->retina->src)
                ];
            endforeach;
        }
        return $newArray;
    }
    public function getSizeHumanAttribute()
    {
        return \App\Libraries\Size\SizeHuman::parse($this->size);
    }

    public function getThumbnailsAttribute($value)
    {
        return collect(json_decode($value));
    }

    public function mediatable()
    {
        return $this->morphTo();
    }

    public function user()
    {
		return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Media', 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Media', 'parent_id')->with('children');
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\Media', 'parent_id')->with('childrens');
    }

    public function getRetinaAttribute()
    {
        return $this->children()->first();
    }
}
