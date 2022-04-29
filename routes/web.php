<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [
    'as' => 'home',
    'uses' => '\App\Http\Controllers\IndexController@index'
]);

Route::get('home', function(){
    return redirect(route('user.index'), 301);
});

////////////////////////////////////
// Авторизация и Регистрация
////////////////////////////////////

Route::group([
    'prefix' => 'auth',
], function(){

    Auth::routes([
        'login'    => true, 
        'logout'   => true, 
        'register' => false, 
        'reset'    => true,   // for resetting passwords
        'confirm'  => true,  // for additional password confirmations
        'verify'   => true,  // for email verification
    ]);

    Route::get('register', [
        'as'    => 'register',
        'uses'  => '\App\Http\Controllers\Auth\RegisterController@showRegistrationForm'
    ]);

    Route::post('is-unique', [
        'as'    => 'auth.isunique',
        'uses'  => '\App\Http\Controllers\Auth\RegisterController@checkIsUnique'
    ]);

    Route::post('register', [
        'uses'  => '\App\Http\Controllers\Auth\RegisterController@register'
    ]);
    
});

////////////////////////////////////
// Новости
////////////////////////////////////

Route::group([
    'prefix' => 'news',
    'as' => 'news.'
], function(){

    Route::get('/', [
        'as'    => 'index',
        'uses'  => '\App\Http\Controllers\NewsController@index'
    ]);

    Route::get('{id}', [
        'as'    => 'show',
        'uses'  => '\App\Http\Controllers\NewsController@show'
    ]);
    
});

////////////////////////////////////
// Стать спикером
////////////////////////////////////

Route::group([
    'prefix' => 'statspikerom',
    'as' => 'speaker.'
], function(){

    Route::get('/', [
        'as'    => 'index',
        'uses'  => '\App\Http\Controllers\SpeakerController@index'
    ]);

    Route::post('store', [
        'as'    => 'store',
        'uses'  => '\App\Http\Controllers\SpeakerController@store'
    ]);
    
});

////////////////////////////////////
// Проекты сообщества
////////////////////////////////////

Route::group([
    'prefix' => 'projectsnews',
    'as' => 'community-project.'
], function(){

    Route::get('/', [
        'as'    => 'index',
        'uses'  => '\App\Http\Controllers\CommunityProjectController@index'
    ]);

    Route::get('{id}', [
        'as'    => 'show',
        'uses'  => '\App\Http\Controllers\CommunityProjectController@show'
    ]);
    
});

////////////////////////////////////
// Сервисы
////////////////////////////////////

Route::group([
    'prefix' => 'services',
    'as' => 'service.'
], function(){

    Route::get('/', [
        'as'    => 'index',
        'uses'  => '\App\Http\Controllers\ServiceController@index'
    ]);
    
    Route::group([
        'prefix' => 'digitals',
        'as' => 'digital.'
    ], function(){

        Route::group([
            'prefix' => 'passports',
            'as' => 'passport.'
        ], function(){
            
            Route::get('/', [
                'as'    => 'index',
                'uses'  => '\App\Http\Controllers\ServiceDigitalPassportController@index',
            ]);
            
            Route::get('create', [
                'as'    => 'create',
                'uses'  => '\App\Http\Controllers\ServiceDigitalPassportController@create',
            ]);

            Route::group([
                'prefix' => '{passport:slug}',
                'as' => 'show.'
            ], function(){

                Route::get('/', [
                    'as'    => 'index',
                    'uses'  => '\App\Http\Controllers\ServiceDigitalPassportController@show',
                ]);

                Route::get('download', [
                    'as'    => 'download',
                    'uses'  => '\App\Http\Controllers\ServiceDigitalPassportController@download',
                ]);

                Route::get('edit', [
                    'as'    => 'edit',
                    'uses'  => '\App\Http\Controllers\ServiceDigitalPassportController@edit',
                ]);

            });

        });

        Route::group([
            'prefix' => 'ideas',
            'as' => 'idea.'
        ], function(){

            Route::get('/', [
                'as'    => 'index',
                'uses'  => '\App\Http\Controllers\ServiceDigitalIdeaController@index',
            ]);

            Route::get('create', [
                'as'    => 'create',
                'uses'  => '\App\Http\Controllers\ServiceDigitalIdeaController@create',
            ]);

            Route::group([
                'prefix' => '{passport:slug}',
                'as' => 'show.'
            ], function(){

                Route::get('/', [
                    'as'    => 'index',
                    'uses'  => '\App\Http\Controllers\ServiceDigitalIdeaController@show',
                ]);

                Route::get('edit', [
                    'as'    => 'edit',
                    'uses'  => '\App\Http\Controllers\ServiceDigitalIdeaController@edit',
                ]);

            });

        });

    });

});

////////////////////////////////////
// База Знаний
////////////////////////////////////

Route::group([
    'prefix' => 'knowledge-base',
    'as' => 'knowledge-base.',
], function(){

    Route::get('/', [
        'as'    => 'index',
        'uses'  => '\App\Http\Controllers\KnowledgeBaseController@index',
    ]);

    Route::group([
        'prefix' => '{post:slug}',
        'as' => 'show.',
    ], function(){

        Route::get('/', [
            'as'    => 'index',
            'uses'  => '\App\Http\Controllers\KnowledgeBaseController@show'
        ]);

        Route::get('file-download', [
            'as'    => 'file-download',
            'uses'  => '\App\Http\Controllers\KnowledgeBaseController@FileDownload'
        ]);

    });

});

////////////////////////////////////
// Только для авторизованных юзеров
////////////////////////////////////

Route::group(['middleware' => [
    'auth',
    'verified'
]], function(){

    ////////////////////////////////////
    // Участники
    ////////////////////////////////////

    Route::group([
        'prefix' => 'participants',
        'as' => 'participant.'
    ], function(){

        Route::get('/', [
            'as'    => 'index',
            'uses'  => '\App\Http\Controllers\ParticipantController@index'
        ]);

        Route::get('{user:slug}', [
            'as'    => 'show',
            'uses'  => '\App\Http\Controllers\ParticipantController@show'
        ]);
        
    });

    ////////////////////////////////////
    // Профиль юзера
    ////////////////////////////////////

    Route::group([
        'prefix' => 'profile',
        'as' => 'user.'
    ], function(){

        Route::get('/', [
            'as'    => 'index',
            'uses'  => '\App\Http\Controllers\UserController@index'
        ]);

        ////////////////////////////////////
        // Мои документы
        ////////////////////////////////////

        Route::group([
            'prefix' => 'documents',
            'as' => 'document.'
        ], function(){

            Route::get('/', [
                'as'    => 'index',
                'uses'  => '\App\Http\Controllers\UserController@documents'
            ]);

        });

        ////////////////////////////////////
        // Уведомления
        ////////////////////////////////////

        Route::group([
            'prefix' => 'notifications',
            'as' => 'notification.'
        ], function(){

            Route::get('/', [
                'as'    => 'index',
                'uses'  => '\App\Http\Controllers\UserController@notifications'
            ]);

        });

    });
    
});

////////////////////////////////////
// Мотивация
////////////////////////////////////

Route::get('/motivation', [\App\Http\Controllers\MotivationController::class, 'index']);

Route::group(['middleware' => [
    'auth',
    'verified'
]], function(){ 

});
Route::post('/motivation-get-prize', [\App\Http\Controllers\MotivationTiketController::class, 'storePrize'])->name('motivation-get-prize');