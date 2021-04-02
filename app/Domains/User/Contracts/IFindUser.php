<?php


namespace App\Domains\User\Contracts;


interface IFindUser
{
    /**
     * Fetch the specified resource.
     *
     * @param int $id
     * @return mixed
     */
    public function findUserById(int $id);

    /**
     * Fetch the specified resource.
     *
     * @param $uuid
     * @return mixed
     */
    public function findUserByUuid($uuid);
}
