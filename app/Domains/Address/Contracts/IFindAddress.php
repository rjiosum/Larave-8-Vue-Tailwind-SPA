<?php


namespace App\Domains\Address\Contracts;


interface IFindAddress
{
    /**
     * Fetch the specified resource.
     *
     * @param int $id
     * @return mixed
     */
    public function findAddressById(int $id);

    /**
     * Fetch the specified resource.
     *
     * @param $uuid
     * @return mixed
     */
    public function findAddressByUuid($uuid);
}
