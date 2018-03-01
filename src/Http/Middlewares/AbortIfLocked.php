<?php

namespace Runsite\CMF\Http\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class AbortIfLocked
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
        if(Auth::user()->is_locked)
        {
            return new Response(view('runsite::errors.forbidden'));
        }

        return $next($request);
    }
}
