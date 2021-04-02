<?php


namespace App\Domains\Address\Contracts;


interface ICreateAddress
{
    /**
     * Store a newly created resource in storage.
     *
     * @param array $params
     * @return mixed
     */
    public function createAddress(array $params);
}
