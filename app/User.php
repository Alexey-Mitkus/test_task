<?php

namespace App;

use DB;
use QCod\ImageUp\HasImageUploads;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;

use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use App\Http\Resources\FieldResource as FieldResource;

use App\beilec\Testsystem\be_test_passage;
use App\beilec\Testsystem\be_test_template;
use App\beilec\Testsystem\be_test_answer;
use App\beilec\Testsystem\be_test_question_section;

use App\Models\Project;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasImageUploads;

    protected $connection = 'mysql_old';

    protected $fillable = [
        'name', 'email', 'password', 'ref_name', 'cover', 'avatar'
    ];
	
	protected $appends = ['fullName', 'exp'];

	protected $autoUploadImages = false;

	// какой диск использовать для загрузки, можно изменить с помощью параметров поля 
    protected  $imagesUploadDisk = 'local' ;

	protected static $imageFields = [
        'avatar' => [
            // width to resize image after upload
            'width' => 202,

            // height to resize image after upload
            'height' => 202,

            // set true to crop image with the given width/height and you can also pass arr [x,y] coordinate for crop.
            'crop' => true,

            // what disk you want to upload, default config('imageup.upload_disk')
            'disk' => 'public',

            // a folder path on the above disk, default config('imageup.upload_directory')
            'path' => 'avatars',

            // placeholder image if image field is empty
            'placeholder' => '/images/avatar-placeholder.svg',

            // validation rules when uploading image
            'rules' => 'image|max:20000',

            // override global auto upload setting coming from config('imageup.auto_upload_images')
            'auto_upload' => false,


        ],
        'cover',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	
    public function postslikes()
    {
    	return $this->belongsToMany('App\KbPost', 'kb_posts_has_likes', 'user_id', 'post_id');
    }
}
