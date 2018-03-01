<?php

namespace Runsite\CMF\Http\Middlewares;

use Closure;

class CheckAdminIP
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
        $allowed_ips = config('runsite.ip_auth_limit.allow_ips');
        $disallowed_ips = config('runsite.ip_auth_limit.disallow_ips');

        if(! is_array($allowed_ips) and ! is_array($disallowed_ips))
        {
            return $next($request);
        }

        if((! is_array($disallowed_ips) or in_array($request->ip(), $disallowed_ips)) and ! in_array($request->ip(), $allowed_ips))
        {
            return abort(404);
        }

        return $next($request);
    }
}
