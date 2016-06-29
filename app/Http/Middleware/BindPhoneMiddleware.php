<?php

namespace App\Http\Middleware;

use Closure;

class BindPhoneMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next,  $guard = null)
    {
        $is_first = \Auth::guard($guard)->user()->is_first;

        if (!$is_first) {
            return redirect('binding');
        }

        return $next($request);
    }
}
