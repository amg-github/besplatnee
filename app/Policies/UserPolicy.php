<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends ModelPolicy
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

    public function show(User $userRequestor, User $userResponser) {
        return $userResponser->id == $userRequestor->id 
            || $userRequestor->checkPolicy('user_show');
    }

    public function edit(User $userRequestor, User $userResponser) {
        return $userRequestor->id == $userResponser->id 
            || $userRequestor->checkPolicy('user_edit');
    }

    public function remove(User $userRequestor, User $userResponser) {
        return $userResponser->id == $userRequestor->id 
            || $userRequestor->checkPolicy('user_remove');
    }

    public function activate(User $userRequestor, User $userResponser) {
        return $userResponser->id == $userRequestor->id 
            || $userRequestor->checkPolicy('user_activate');
    }

    public function blocked(User $userRequestor, User $userResponser) {
        return $userResponser->id == $userRequestor->id 
            || $userRequestor->checkPolicy('user_blocked');
    }

    public function unblocked(User $userRequestor, User $userResponser) {
        return $userRequestor->checkPolicy('user_unblocked');
    }

    public function policies(User $userRequestor, User $userResponser) {
        return $userRequestor->checkPolicy('user_policies');
    }

    public function create(User $userRequestor, User $userResponser) {
        return $userRequestor->checkPolicy('user_create');
    }
}
