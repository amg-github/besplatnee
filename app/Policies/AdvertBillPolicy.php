<?php

namespace App\Policies;

use App\User;
use App\AdvertBill;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvertBillPolicy
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

    public function show(User $user, AdvertBill $bill) {
        return $user->checkPolicy('advert_bill_show');
    }

    public function edit(User $user, AdvertBill $bill) {
        return $user->checkPolicy('advert_bill_edit');
    }

    public function remove(User $user, AdvertBill $bill) {
        return $user->checkPolicy('advert_bill_remove');
    }
}
