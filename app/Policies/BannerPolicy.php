<?php

namespace App\Policies;

use App\User;
use App\Banner;
use Illuminate\Auth\Access\HandlesAuthorization;

class BannerPolicy
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

    public function show(User $user, Banner $banner) {
        return $banner->creator_id == $user->id 
            || $user->checkPolicy('advert_show');
    }

    public function create(User $user, Banner $banner) {
        return $user->checkPolicy('banner_create');
    }

    public function edit(User $user, Banner $banner) {
        return $banner->creator_id == $user->id 
            || $user->checkPolicy('banner_edit');
    }

    public function remove(User $user, Banner $banner) {
        return $user->checkPolicy('banner_remove');
    }

    public function give(User $user, Banner $banner) {
        return $user->checkPolicy('banner_bind');
    }

    public function approved(User $user, Banner $banner) {
        return $user->checkPolicy('banner_approved');
    }
}
