<?php

namespace Runsite\CMF\Http\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;

class AbortFrontend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        

        return $next($request);
    }
}
