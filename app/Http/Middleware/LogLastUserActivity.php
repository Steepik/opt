<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class LogLastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() and Auth::user()->is_admin == 0) {
            Redis::set('uonline_' . Auth::user()->id, Auth::user()->id, 'EX', 600); // set user online 10 minutes
        }

        return $next($request);
    }
}
