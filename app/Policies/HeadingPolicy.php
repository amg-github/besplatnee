<?php

namespace App\Policies;

use App\User;
use App\Heading;
use Illuminate\Auth\Access\HandlesAuthorization;

class HeadingPolicy
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

    public function show(User $user, Heading $heading) {
        return $user->checkPolicy('heading_show');
    }

     public function create(User $user, Heading $heading) {
        return $user->checkPolicy('heading_create');
    }

    public function edit(User $user, Heading $heading) {
        return $user->checkPolicy('heading_edit');
    }

    public function remove(User $user, Heading $heading) {
        return $user->checkPolicy('heading_remove');
    }
}
