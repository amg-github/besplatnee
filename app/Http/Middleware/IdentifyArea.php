<?php

namespace App\Http\Middleware;

use Config;
use Session;
use Closure;
use \App\Models\Settings;
use \App\Models\GeoObject;
use \App\Models\Language;
use \App\Repositories\LanguageRepository;
use \App\Repositories\GeoObjectRepository;

class IdentifyArea
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
        $this->handleLanguage($request);
        $this->handleCity($request);

        return $next($request);
    }

    public function handleLanguage($request) {
        $language = (new LanguageRepository)->getFromSession();

        \App::setLocale($language->code);
        Config::set('language', $language);
    }

    public function handleCity($request) {
        list($alias) = explode('.', $request->getHost());
        $city = (new GeoObjectRepository)->findCityByAlias($alias);

        if($city) {
            Config::set('city', $city);
        } else {
            //abort(404);
        }

        // для работы в старом режиме
        if($alias == 'besplatnee') { $alias = 'moskva'; }

        $city = \App\GeoObject::where('active', 1)->where('alias', $alias)->first();

        if($city) {
            Config::set('area', $city);
        } else {
            abort(404);
        }
    }
}
