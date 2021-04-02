<?php


namespace App\Domains\Address\Contracts;


interface IFetchAddress
{
    /**
     * Fetch a listing of the user resource.
     *
     * @param int $perPage
     * @param string $sortBy
     * @param string $sortDirection
     * @return mixed
     */
    public function fetchUserAddresses(int $perPage = 10, string $sortBy = 'id', string $sortDirection = 'desc');

}
