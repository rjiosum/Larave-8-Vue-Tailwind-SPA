<?php


namespace App\Domains\Address\Contracts;


interface IDeleteAddress
{
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteAddress(int $id);
}
