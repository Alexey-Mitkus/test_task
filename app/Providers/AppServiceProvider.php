<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(255);

        \Jenssegers\Date\Date::setlocale(config('app.locale'));
        \Carbon\Carbon::setLocale(config('app.locale'));

        if( !\Illuminate\Support\Facades\App::environment('local') && env('APP_USE_HTTPS', false) || env('APP_USE_HTTPS', false) )
        {
            //\Illuminate\Support\Facades\URL::forceSchema('https');
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
