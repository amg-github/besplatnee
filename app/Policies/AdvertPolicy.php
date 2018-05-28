<?php

namespace App\Policies;

use App\User;
use App\Advert;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvertPolicy
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

    public function show(User $user, Advert $advert) {
        return $advert->owner_id == $user->id 
            || $advert->creator_id == $user->id 
            || $user->checkPolicy('advert_show');
    }

    public function create(User $user, Advert $advert) {
        return $user->checkPolicy('advert_create');
    }

    public function edit(User $user, Advert $advert) {
        return $advert->owner_id == $user->id 
            || $advert->creator_id == $user->id 
            || $user->checkPolicy('advert_edit');
    }

    public function remove(User $user, Advert $advert) {
        return $user->checkPolicy('advert_remove');
    }

    public function give(User $user, Advert $advert) {
        return $user->checkPolicy('advert_bind');
    }

    public function approved(User $user, Advert $advert) {
        return $user->checkPolicy('advert_approved');
    }

    public function pickup(User $user, Advert $advert) {
        return $advert->owner_id == $user->id 
            || $advert->creator_id == $user->id 
            || $user->checkPolicy('advert_pickup');
    }
}
