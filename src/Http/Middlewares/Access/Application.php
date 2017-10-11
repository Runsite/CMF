<?php

namespace Runsite\CMF\Http\Middlewares\Access;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Runsite\CMF\Models\Application as ApplicationModel;

class Application
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $application_and_level
     * @return mixed
     */
    public function handle($request, Closure $next, $application_and_level = null)
    {
        $parts = explode(':', $application_and_level);
        $application = ApplicationModel::where('name', $parts[0])->first();
        $level = $parts[1];

        if(!Auth::user()->access()->application($application)->{$level})
        {
            return new Response(view('runsite::errors.forbidden'));
        }
        
        return $next($request);
    }
}
