<?php


namespace App\Domains\User\Actions;


use App\Models\User;
use App\Domains\User\Contracts\IFindUser;

class FindUser implements IFindUser
{
    /**
     * @var User
     */
    private $user;

    /**
     * FindUser constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Fetch the specified resource.
     *
     * @param int $id
     * @return mixed
     */
    public function findUserById(int $id)
    {
        return $this->user->findOrFail($id);
    }

    /**
     * Fetch the specified resource.
     *
     * @param $uuid
     * @return mixed
     */
    public function findUserByUuid($uuid)
    {
        return $this->user->where('uuid', '=', $uuid)->firstOrFail();
    }
}
