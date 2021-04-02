<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Address $address
     * @return mixed
     */
    public function view(User $user, Address $address)
    {
        return $user->ownsAddress($address)
            ? Response::allow()
            : Response::deny('You do not own this address.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Address $address
     * @return mixed
     */
    public function update(User $user, Address $address)
    {
        return $user->ownsAddress($address)
            ? Response::allow()
            : Response::deny('You do not own this address.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Address $address
     * @return mixed
     */
    public function delete(User $user, Address $address)
    {
        return $user->ownsAddress($address)
            ? Response::allow()
            : Response::deny('You do not own this address.');
    }
}
