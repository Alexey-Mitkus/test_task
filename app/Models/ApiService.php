<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ApiService extends Model
{
    use Sluggable;

	protected $table = 'api_services';

    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'type_id',
        'target_id',
        'target_type',
        'options',
        'slug',
    ];

    protected $hidden = [];
    protected $dates = [];
    
    protected $casts = [];

    const CONST_TYPE_VK_COM = 100;
    const CONST_TYPE_HH_RU = 200;

    protected $typeLabels = [
        self::CONST_TYPE_VK_COM => 'Vk.com',
        self::CONST_TYPE_HH_RU => 'HH.ru'
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
        }while( ApiService::where('slug', $uid)->first() instanceof ApiService );
        
        return $uid;
    }

    public function getOptionsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function target()
    {
        return $this->morphTo();
    }
}
