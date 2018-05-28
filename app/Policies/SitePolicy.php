<?php

namespace App\Policies;

use App\User;
use App\Site;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, Site $site) {
        return $user->checkPolicy('site_create');
    }

    public function edit(User $user, Site $site) {
        return $site->owner_id == $user->id 
            || $site->creator_id == $user->id 
            || $user->checkPolicy('site_create');
    }

    public function show(User $user, Site $site) {
        return $site->owner_id == $user->id 
            || $site->creator_id == $user->id 
            || $user->checkPolicy('site_show');
    }

    public function remove(User $user, Site $site) {
        return $site->owner_id == $user->id 
            || $site->creator_id == $user->id 
            || $user->checkPolicy('site_remove');
    }

    public function bind(User $user, Site $site) {
        return $user->checkPolicy('site_bind');
    }
}
