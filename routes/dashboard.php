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

Route::group([
    'middleware' => ['auth', 'verified'],
    'prefix' => 'dashboard',
    'as' => 'dashboard.',
], function(){

    Route::get('/', [
        'as'    => 'index',
        'uses'  => '\App\Http\Controllers\Dashboard\IndexController@index'
    ]);

    ////////////////////////////////////
    // Уведомления
    ////////////////////////////////////

    Route::group([
        'prefix' => 'notifications',
        'as' => 'notification.'
    ], function(){

        Route::get('/', [
            'as'    => 'index',
            'uses'  => '\App\Http\Controllers\Dashboard\NotificationController@index'
        ]);

    });

    ////////////////////////////////////
    // Обновления
    ////////////////////////////////////

    Route::group([
        'prefix' => 'events',
        'as' => 'event.',
    ], function(){

        Route::get('/', [
            'as'    => 'index',
            'uses'  => '\App\Http\Controllers\Dashboard\EventController@index'
        ]);

    });

    ////////////////////////////////////
    // Пользователи
    ////////////////////////////////////

    Route::group([
        'prefix' => 'users',
        'as' => 'user.',
    ], function(){

        Route::get('/', [
            'as'    => 'index',
            'uses'  => '\App\Http\Controllers\Dashboard\UserController@index'
        ]);

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
            'uses'  => '\App\Http\Controllers\Dashboard\KnowledgeBaseController@index'
        ]);

    });

});