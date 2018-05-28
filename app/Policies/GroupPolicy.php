<?php

namespace App\Policies;

use App\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy extends ModelPolicy
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

    public function show(User $user, Group $group) {
        return $user->checkPolicy('group_show');
    }

    public function edit(User $user, Group $group) {
        return $user->checkPolicy('group_edit');
    }

    public function remove(User $user, Group $group) {
        return $user->checkPolicy('group_remove');
    }

    public function create(User $user, Group $group) {
        return $user->checkPolicy('group_create');
    }
}
