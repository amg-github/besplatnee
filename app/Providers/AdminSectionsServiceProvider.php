<?php

namespace App\Providers;

use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $sections = [
        \App\User::class => 'App\Http\Sections\Users',
        \App\Group::class => 'App\Http\Sections\Groups',
        \App\City::class => 'App\Http\Sections\Cities',
        \App\Advert::class => 'App\Http\Sections\Arverts',
        \App\Heading::class => 'App\Http\Sections\Headings',
        \App\Property::class => 'App\Http\Sections\HeadingProperties',
        \App\AdvertMedia::class => 'App\Http\Sections\AdvertMedia',
    ];

    /**
     * Register sections.
     *
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
    	//

        parent::boot($admin);
    }
}
