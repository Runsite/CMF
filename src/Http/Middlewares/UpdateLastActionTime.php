<?php

namespace Runsite\CMF\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Runsite\CMF\Models\User\User;
use Carbon\Carbon;

class UpdateLastActionTime
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
        User::where('id', Auth::user()->id)->update([
          'last_action_at' => Carbon::now(),
        ]);

        return $next($request);
    }
}
