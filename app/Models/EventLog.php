<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventLog extends Model
{
    use Sluggable;
    use SoftDeletes;

	protected $table = 'events_log';

    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'user_type',
        'user_id',
        'target_type',
        'target_id',
        'category_id',
        'event_type',
        'old_values',
        'new_values',
        'processed_user_id',
        'message',
        'status_id',
        'slug',
    ];

    protected $hidden = [];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    const CONST_EVENT_TYPE_CREATE = 100;
    const CONST_EVENT_TYPE_UPDATE = 200;
    const CONST_EVENT_TYPE_DELETE = 300;

    const CONST_STATUS_CONFIRMED = 200;
    const CONST_STATUS_DECLINED = 500;
    const CONST_STATUS_WAITING = 100;

    protected $eventTypesTranslationLabels = [
        self::CONST_EVENT_TYPE_CREATE => 'Создание',
        self::CONST_EVENT_TYPE_UPDATE => 'Обновление',
        self::CONST_EVENT_TYPE_DELETE => 'Удаление',
	];

    protected $eventTypesLabels = [
        self::CONST_EVENT_TYPE_CREATE => 'create',
        self::CONST_EVENT_TYPE_UPDATE => 'update',
        self::CONST_EVENT_TYPE_DELETE => 'destroy',
	];

    protected $eventStatusTranslationLabels = [
        self::CONST_STATUS_CONFIRMED => 'Принято',
        self::CONST_STATUS_DECLINED => 'Отменено',
        self::CONST_STATUS_WAITING => 'Ожидание',
	];

    protected $eventStatusLabels = [
        self::CONST_STATUS_CONFIRMED => 'confirmed',
        self::CONST_STATUS_DECLINED => 'declined',
        self::CONST_STATUS_WAITING => 'waiting',
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
        }while( EventLog::where('slug', $uid)->first() instanceof EventLog );
        
        return $uid;
    }

    public function getTypeAttribute()
    {
        return $this->event_type;
    }
    
    public function getTypeSlugAttribute()
    {
        return $this->eventTypesLabels[$this->attributes['event_type']];
    }

    public function getTypeTranslationAttribute()
    {
        return $this->eventTypesTranslationLabels[$this->attributes['event_type']];
    }

    public function getStatusAttribute()
    {
        return $this->status_id;
    }

    public function getStatusSlugAttribute()
    {
        return $this->eventStatusLabels[$this->attributes['status_id']];
    }

    public function getStatusTranslationAttribute()
    {
        return $this->eventStatusTranslationLabels[$this->attributes['status_id']];
    }

    public function user()
    {
		return $this->morphTo()->withTrashed();
    }

    public function target()
    {
		return $this->morphTo()->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo('App\Models\EventLogCategory', 'category_id');
    }

    public function handler()
    {
        return $this->belongsTo('App\Models\User', 'processed_user_id');
    }
}
