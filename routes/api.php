<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
	'as' => 'api.',
], function(){

    Route::get('/', function(){
        return abort(404);
    });

    ////////////////////////////////////
    // Новости
    ////////////////////////////////////

    Route::group([
        'prefix' => 'news',
        'as' => 'news.',
    ], function(){

        Route::get('/', [
            'as' => 'index',
            'uses' => '\App\Http\Controllers\Api\NewsController@index',
        ]);

        Route::get('search', [
            'as' => 'search',
            'uses' => '\App\Http\Controllers\Api\NewsController@search',
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
            'uses'  => '\App\Http\Controllers\Api\CommunityProjectController@index'
        ]);

        Route::get('search', [
            'as' => 'search',
            'uses' => '\App\Http\Controllers\Api\CommunityProjectController@search',
        ]);
        
    });

    ////////////////////////////////////
    // База знаний
    ////////////////////////////////////

    Route::group([
        'prefix' => 'knowledge-base',
        'as' => 'knowledge-base.',
    ], function(){


        Route::get('/', [
            'as' => 'index',
            'uses' => '\App\Http\Controllers\Api\KnowledgeBaseController@index',
        ]);

        Route::get('search-link', [
            'as' => 'search-link',
            'uses' => '\App\Http\Controllers\Api\KnowledgeBaseController@searchLink'
        ]);

        Route::group([
            'middleware' => 'auth:api',
        ], function(){

            Route::post('store', [
                'as'    => 'store',
                'uses'  => '\App\Http\Controllers\Api\KnowledgeBaseController@store',
            ]);

            Route::post('store-admin', [
                'as'    => 'store.admin',
                'uses'  => '\App\Http\Controllers\Api\KnowledgeBaseController@DashboardStore',
            ]);

            Route::group([
                'prefix' => '{post:slug}',
                'as' => 'show.',
            ], function(){

                Route::get('edit', [
                    'as'    => 'edit',
                    'uses'  => '\App\Http\Controllers\Api\KnowledgeBaseController@edit'
                ]);

                Route::post('update', [
                    'as' => 'update',
                    'uses' => '\App\Http\Controllers\Api\KnowledgeBaseController@update'
                ]);

                Route::delete('destroy', [
                    'as'    => 'destroy',
                    'uses'  => '\App\Http\Controllers\Api\KnowledgeBaseController@destroy'
                ]);

                Route::post('reject', [
                    'as'    => 'reject',
                    'uses'  => '\App\Http\Controllers\Api\KnowledgeBaseController@reject'
                ]);

                Route::post('approved', [
                    'as'    => 'approved',
                    'uses'  => '\App\Http\Controllers\Api\KnowledgeBaseController@approved'
                ]);

                Route::post('sulikes', [
                    'as'    => 'sulikes',
                    'uses'  => '\App\Http\Controllers\Api\KnowledgeBaseController@setUnsetLikes'
                ]);

                Route::group([
                    'prefix' => 'medias',
                    'as' => 'media.',
                ], function(){

                    Route::delete('destroy', [
                        'as'    => 'destroy',
                        'uses'  => '\App\Http\Controllers\Api\KnowledgeBaseController@FileDestroy'
                    ]);

                });

            });

		});

    });

    ////////////////////////////////////
    // Список организаций (мед.)
    ////////////////////////////////////

    Route::group([
        'prefix' => 'organizations',
        'as' => 'organizations.'
    ], function(){


        Route::get('/', [
            'as' => 'index',
            'uses' => '\App\Http\Controllers\Api\OrganizationController@index'
        ]);

    });

    ////////////////////////////////////
    // Список образовательных учреждений
    ////////////////////////////////////

    Route::group([
        'prefix' => 'educations',
        'as' => 'education.'
    ], function(){


        Route::get('/', [
            'as' => 'index',
            'uses' => '\App\Http\Controllers\Api\EducationController@index'
        ]);

        Route::get('categories', [
            'as' => 'categories',
            'uses' => '\App\Http\Controllers\Api\EducationController@categories'
        ]);

    });

    ////////////////////////////////////
    // Сервисы
    ////////////////////////////////////

    Route::group([
        'prefix' => 'services',
        'as' => 'service.'
    ], function(){

        ////////////////////////////////////
        // Цифровые
        ////////////////////////////////////

        Route::group([
            'prefix' => 'digitals',
            'as' => 'digital.'
        ], function(){


            ////////////////////////////////////
            // Паспорт
            ////////////////////////////////////

            Route::group([
                'prefix' => 'passports',
                'as' => 'passport.'
            ], function(){
                
                Route::group([
                    'middleware' => 'auth:api',
                ], function(){

                    Route::post('store', [
                        'as'    => 'store',
                        'uses'  => '\App\Http\Controllers\Api\ServiceDigitalPassportController@store',
                    ]);

                    Route::group([
                        'prefix' => '{passport:slug}',
                        'as' => 'show.'
                    ], function(){

                        Route::post('update', [
                            'as'    => 'update',
                            'uses'  => '\App\Http\Controllers\Api\ServiceDigitalPassportController@update',
                        ]);
    
                        Route::post('copy', [
                            'as'    => 'copy',
                            'uses'  => '\App\Http\Controllers\Api\ServiceDigitalPassportController@copy',
                        ]);

                        Route::delete('destroy', [
                            'as'    => 'destroy',
                            'uses'  => '\App\Http\Controllers\Api\ServiceDigitalPassportController@destroy',
                        ]);
                    });

                });
                
            });
    
            ////////////////////////////////////
            // Идея
            ////////////////////////////////////

            Route::group([
                'prefix' => 'ideas',
                'as' => 'idea.'
            ], function(){
    
                Route::group([
                    'middleware' => 'auth:api',
                ], function(){

                    Route::post('store', [
                        'as'    => 'store',
                        'uses'  => '\App\Http\Controllers\Api\ServiceDigitalIdeaController@store',
                    ]);

                    Route::group([
                        'prefix' => '{passport:slug}',
                        'as' => 'show.'
                    ], function(){

                        Route::post('update', [
                            'as'    => 'update',
                            'uses'  => '\App\Http\Controllers\Api\ServiceDigitalIdeaController@update',
                        ]);

                        Route::post('copy', [
                            'as'    => 'copy',
                            'uses'  => '\App\Http\Controllers\Api\ServiceDigitalIdeaController@copy',
                        ]);

                        Route::delete('destroy', [
                            'as'    => 'destroy',
                            'uses'  => '\App\Http\Controllers\Api\ServiceDigitalIdeaController@destroy',
                        ]);
    
                    });

                });

            });

        });

        ////////////////////////////////////
        // Формы
        ////////////////////////////////////

        Route::group([
            'prefix' => 'tickets',
            'as' => 'ticket.'
        ], function(){
        
            Route::get('/', [
                'as'    => 'index',
                'uses'  => '\App\Http\Controllers\Api\TicketController@index'
            ]);
    
            Route::post('store', [
                'as'    => 'store',
                'uses'  => '\App\Http\Controllers\Api\TicketController@store'
            ]);
            
        });

    });

    ////////////////////////////////////
    // Пользователи
    ////////////////////////////////////

    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function(){

        Route::get('/', [
            'as' => 'index',
            'uses' => '\App\Http\Controllers\Api\UserController@index'
        ]);

        Route::get('auth', [
            'as' => 'auth',
            'middleware' => 'auth:api',
            'uses' => '\App\Http\Controllers\Api\UserController@auth'
        ]);

        Route::group([
            'prefix' => 'managments',
            'as' => 'managment.'
        ], function(){

            Route::get('roles', [
                'as'    => 'roles',
                'uses'  => '\App\Http\Controllers\Api\UserManagmentController@roles'
            ]);
        
        });

        Route::group([
            'prefix' => 'fields',
            'as' => 'field.'
        ], function(){

            Route::get('/', [
                'as'    => 'index',
                'uses'  => '\App\Http\Controllers\Api\UserController@getFields'
            ]);
        
        });

        Route::group([
            'prefix' => '{user:slug}',
            'as' => 'show.'
        ], function(){

            Route::get('/', [
                'as' => 'index',
                'uses' => '\App\Http\Controllers\Api\UserController@show'
            ]);

            Route::get('notifications', [
                'as' => 'notifications',
                'uses'  => '\App\Http\Controllers\Api\UserController@notifications'
            ]);

            Route::get('passports', [
                'as' => 'passports',
                'uses'  => '\App\Http\Controllers\Api\UserController@passports'
            ]);

            Route::post('update', [
                'as' => 'update',
                'uses'  => '\App\Http\Controllers\Api\UserController@update'
            ]);

            Route::post('verify', [
                'as' => 'verify',
                'uses'  => '\App\Http\Controllers\Api\UserController@verify'
            ]);

            Route::delete('destroy', [
                'as' => 'destroy',
                'uses'  => '\App\Http\Controllers\Api\UserController@destroy'
            ]);

            Route::group([
                'prefix' => 'educations',
                'as' => 'education.'
            ], function(){

                Route::group([
                    'prefix' => '{UserEducation:slug}',
                    'as' => 'show.'
                ], function(){
    
                    Route::delete('destroy', [
                        'as' => 'destroy',
                        'uses'  => '\App\Http\Controllers\Api\UserController@EducationDestroy'
                    ]);
    
                });

            });

            Route::group([
                'prefix' => 'notifications',
                'as' => 'notification.'
            ], function(){

                Route::post('push-to-fill-profile', [
                    'as' => 'push-to-fill-profile',
                    'uses'  => '\App\Http\Controllers\Api\UserController@PushToFillProfile'
                ]);
                
            });
        });

    });


    ////////////////////////////////////
    // Уведомления
    ////////////////////////////////////

    Route::group([
        'prefix' => 'notifications',
        'as' => 'notification.'
    ], function(){

        Route::post('store', [
            'as'    => 'store',
            'uses'  => '\App\Http\Controllers\Api\NotificationController@store'
        ]);

        Route::group([
            'prefix' => '{notification:id}',
            'as' => 'show.'
        ], function(){

            Route::post('read', [
                'as'    => 'read',
                'uses'  => '\App\Http\Controllers\Api\NotificationController@readNotifications'
            ]);

            Route::post('unread', [
                'as'    => 'unread',
                'uses'  => '\App\Http\Controllers\Api\NotificationController@unreadNotifications'
            ]);

            Route::delete('destroy', [
                'as'    => 'destroy',
                'uses'  => '\App\Http\Controllers\Api\NotificationController@destroy'
            ]);

        });

    });

});
