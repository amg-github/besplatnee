<?php

namespace App\Http\Middleware;

use Closure;

class AdminAccessCheck
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
        if(\Auth::check() && \Auth::user()->isAccessToAdminPanel()) {
            if(\Auth::user()->id == 1) {
                \Debugbar::enable();
            }
            return $next($request);
        } else {
            abort(404);
        }
    }
}
