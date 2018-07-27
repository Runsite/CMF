<?php

namespace Runsite\CMF\Http\Middlewares;

use Closure;
use Illuminate\Support\Facades\Route;

class ReplaceMinifyPath
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
        if(str_is('*admin*', Route::getFacadeRoot()->current()->uri()))
        {
            config([
                'minify.config.css_build_path' => '/vendor/runsite/asset/builds-css/',
                'minify.config.css_url_path' => '/vendor/runsite/asset/builds-css/',
                'minify.config.js_build_path' => '/vendor/runsite/asset/builds-js/',
                'minify.config.js_url_path' => '/vendor/runsite/asset/builds-js/',
            ]);
        }

        return $next($request);
    }
}
