<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
  public function handle($request, Closure $next)
  {
      if( auth()->user() && auth()->user()->hasRole('admin') )
      {
        return $next($request);
      }
      return redirect('home')->with('error','Бум!');
  }
}
