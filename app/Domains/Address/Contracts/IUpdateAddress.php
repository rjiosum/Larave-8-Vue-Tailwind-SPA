<?php


namespace App\Domains\Address\Contracts;


interface IUpdateAddress
{
    /**
     * Update the specified resource in storage.
     *
     * @param array $params
     * @return mixed
     */
    public function updateAddress(array $params);
}
