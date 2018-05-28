<?php

namespace App\Policies;

use App\User;
use App\GeoObject;
use Illuminate\Auth\Access\HandlesAuthorization;

class GeoObjectsPolicy
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

    public function show(User $user, GeoObject $geoobject) {
        return $user->checkPolicy('geoobject_show');
    }

     public function create(User $user, GeoObject $geoobject) {
        return $user->checkPolicy('geoobject_create');
    }

    public function edit(User $user, GeoObject $geoobject) {
        return $user->checkPolicy('geoobject_edit');
    }

    public function remove(User $user, GeoObject $geoobject) {
        return $user->checkPolicy('geoobject_remove');
    }
}
