<?php

namespace App\Policies;

use App\User;
use App\AdvertSearchQuery;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvertSearchQueryPolicy
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

    public function list(User $user, AdvertSearchQuery $query) {
        return $user->checkPolicy('advert_search_query_list');
    }

    public function remove(User $user, AdvertSearchQuery $query) {
        return $user->checkPolicy('advert_search_query_remove');
    }

    public function edit(User $user, AdvertSearchQuery $query) {
        return $user->checkPolicy('advert_search_query_edit');
    }

}
