<?php

namespace App\Http\Middleware;

use Closure;

class RedirectToHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        
        if( !\App::environment('local') && env('APP_USE_HTTPS', false) || env('APP_USE_HTTPS', false) )
        {
            if ( !$request->secure() )
            {
                return redirect()->secure($request->path(), 301);
            }
        }
        return $next($request);
    }
}
