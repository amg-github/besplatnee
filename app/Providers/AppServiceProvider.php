<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;
use Auth;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->validatorExtend();
        $this->bladeExtend();
    }

    public function validatorExtend() {

        $this->app['validator']->extend('price', function ($attribute, $value) {
            return preg_match('/^0(\.[0-9]{2}|\,[0-9]{2})?$/', $value) || preg_match('/^([1-9][0-9]{0,2})(\s?\d{3})*?(\.[0-9]{2}|\,[0-9]{2})?$/', $value);
        });

        $this->app['validator']->extend('phone', function ($attribute, $value) {
            return preg_match('/^\+([0-9]+)\s?\(?([0-9]+)\)?\s?\-?([0-9]{3})\-?([0-9]{2})\-?([0-9]{2})$/', $value);
        });

        $this->app['validator']->extend('extended_url', function ($attribute, $value) {
            return preg_match('/^(http:\/\/|https:\/\/)?([a-z0-9-_]+)(\.[a-z0-9-_]+)+(\/[a-zA-Z0-9-_]+)*\/?$/', $value);
        });

        $this->app['validator']->extend('exists_or_in', function ($attribute, $value, $parameters) {
            $in_values = array_slice($parameters, 2);

            if(in_array($value, $in_values)) {
                return true;
            } else {
                return \DB::table($parameters[0])->where($parameters[1], $value)->count() > 0;
            }
        });

        $this->app['validator']->extendImplicit('required_with_unless', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();

            if(isset($data[$parameters[0]]) 
                && $data[$parameters[0]] !== null
                && !in_array(
                    $data[$parameters[0]], 
                    array_slice($parameters, 1)
                )) {

                return isset($data[$attribute])
                    && $data[$attribute] !== null;
            } else { return true; }
        });

        $this->app['validator']->extend('min_format', function ($attribute, $value, $parameters, $validator) {
            if(isset($parameters[0])) {
                $min = $parameters[0];
                $dec_point = isset($parameters[1]) ? $parameters[1] : ' ';
                $thousands_sep = isset($parameters[2]) ? $parameters[2] : ',';

                $value = str_replace($dec_point, '', $value);
                $value = str_replace($thousands_sep, '.', $value);

                return floatval($value) >= $min;

            } else { return true; }
        });

        $this->app['validator']->extend('max_format', function ($attribute, $value, $parameters, $validator) {
            if(isset($parameters[0])) {
                $max = $parameters[0];
                $dec_point = isset($parameters[1]) ? $parameters[1] : ' ';
                $thousands_sep = isset($parameters[2]) ? $parameters[2] : ',';

                $value = str_replace($dec_point, '', $value);
                $value = str_replace($thousands_sep, '.', $value);

                return floatval($value) <= $max;

            } else { return true; }
        });

    }

    public function bladeExtend() {
        Blade::if('permission', function ($environment) {
            return Auth::check() && Auth::user()->checkPolicy($environment);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Facades\Besplatnee::class, function ($app) {
            return new \App\Facades\Besplatnee;
        });
    }
}
