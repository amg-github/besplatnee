<?php

namespace App\Http\Middleware;

use Closure;

class RedirectForOldUrls
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
        if($redirect = \App\Redirect::where('source', '/' . trim($request->path(), '/'))->where('active', true)->first()) {
            if(intval($redirect->target) > 0) {
                if($advert = \App\Advert::find(intval($redirect->target))) {
                    return redirect($advert->getUrl(config('area')->id));
                }
            } else {
                return redirect($redirect->target);
            }
        }

        return $next($request);
    }
}
