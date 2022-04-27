<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Traits\UserFieldTrait;
use App\Traits\ImageRepositoryTrait;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasRoles;
    use Sluggable;
    use SoftDeletes;
    use UserFieldTrait;
    use ImageRepositoryTrait;
    use SearchableTrait;

	protected $table = 'users';
    
    protected $guarded = [
    	'id'
    ];

    protected $fillable = [
        'email',
        'password',
        'api_token',
        'referal',
        'slug',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [];
    protected $appends = [];

    protected $searchable = [

        'columns' => [
            'users.email' => 10
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
            $uid = \Illuminate\Support\Str::uuid();
        }while( User::where('slug', $uid)->first() instanceof User );
        
        return $uid;
    }

    public function getRatingAttribute()
    {
        $sum = 0;
        $sum += $this->fields->sum('pivot.points');

        $educations = $this->educations;
        $job = $this->job;
        $managment = $this->managment;
        
        if( $educations->count() )
        {
            $sum += 25;
        }

        if( $job->count() )
        {

            if( !empty($job->organization_id) || !empty($job->raw_organization) || !empty($job->position) || $job->tags->count() )
            {
                $sum += 25;
            }
            
        }

        if( !empty($managment) )
        {
            if( !empty($managment->getting) && !empty($managment->share) )
            {
                $sum += 10;
            }
            if( $managment->methodologies->count() )
            {
                $sum += 10;
            }
        }
        return $sum;
    }

    public function getAvatarAttribute()
    {
        $pic = $this->pictures('172x172', true);
        if( $pic )
        {
            return $pic->src;
        }
        return null;
    }

    public function getFirstNameAttribute()
    {
        $query = $this->fields()->where('slug', 'first_name');
        return $query->count() ? $query->first() : null;
    }

    public function getLastNameAttribute()
    {
        $query = $this->fields()->where('slug', 'last_name');
        return $query->count() ? $query->first() : null;
    }

    public function getMiddleNameAttribute()
    {
        $query = $this->fields()->where('slug', 'middle_name');
        return $query->count() ? $query->first() : null;
    }

    public function getFullNameAttribute()
    {
        $full_name = collect([]);

        if( !empty($this->last_name) )
        {
            $full_name->push($this->last_name->pivot->value);
        }

        if( !empty($this->first_name) )
        {
            $full_name->push($this->first_name->pivot->value);
        }

        if( !empty($this->middle_name) )
        {
            $full_name->push($this->middle_name->pivot->value);
        }

        return $full_name->count() ? $full_name->implode(' ') : null;
    }

    public function getFullNameShortAttribute()
    {
        $short_full_name = collect([]);

        if( !empty($this->last_name) )
        {
            $short_full_name->push($this->last_name->pivot->value . ' ');
        }

        if( !empty($this->first_name) )
        {
            $short_full_name->push(Str::of($this->first_name->pivot->value)->substr(0, 1)->finish('.'));
        }

        if( !empty($this->middle_name) )
        {
            $short_full_name->push(Str::of($this->middle_name->pivot->value)->substr(0, 1)->finish('.'));
        }

        return $short_full_name->count() ? $short_full_name->implode('') : null;
    }

    public function media()
    {
        return $this->morphMany('App\Models\Media', 'mediatable');
    }

    public function fields()
    {
        return $this->belongsToMany('App\Models\UserField', 'users_fileds_has_users', 'user_id', 'field_id')->Active()->using(\App\Pivots\UserFieldServicePivot::class)->withPivot(['value', 'points', 'is_show']);
    }

    public function job()
    {
        return $this->hasOne('App\Models\UserJob', 'user_id')->with(['tags']);
    }

    public function managment()
    {
        return $this->hasOne('App\Models\UserManagment', 'user_id')->with(['methodologies']);
    }

    public function educations()
    {
        return $this->hasMany('App\Models\UserEducation', 'user_id')->with(['university', 'type']);
    }

    public function passports()
    {
        return $this->hasMany('App\Models\Passport', 'user_id')->with(['organization', 'category', 'directors', 'teams', 'interests', 'objectives', 'results', 'metrics', 'resources', 'budgets', 'risks', 'plans']);
    }

    public function kbpostslikes()
    {
    	return $this->belongsToMany('App\Models\KnowledgeBase\KbPost', 'kb_posts_has_likes', 'user_id', 'post_id');
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
    
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new \App\Notifications\ResetPassword($token));
	}

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmail());
    }
    
    public function getPicturesArrayAttribute()
    {
        return config('users.userpic.sizes');
    }
}
