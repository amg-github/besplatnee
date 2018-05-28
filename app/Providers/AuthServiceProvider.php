<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Group' => 'App\Policies\GroupPolicy',
        'App\Phone' => 'App\Policies\PhonePolicy',
        'App\Advert' => 'App\Policies\AdvertPolicy',
        'App\Banner' => 'App\Policies\BannerPolicy',
        'App\Heading' => 'App\Policies\HeadingPolicy',
        'App\Site' => 'App\Policies\SitePolicy',
        'App\GeoObject' => 'App\Policies\GeoObjectsPolicy',
        'App\AdvertSearchQuery' => 'App\Policies\AdvertSearchQueryPolicy',
        'App\AdvertBill' => 'App\Policies\AdvertBillPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
