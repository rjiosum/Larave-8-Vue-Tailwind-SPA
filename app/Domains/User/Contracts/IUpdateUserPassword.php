<?php


namespace App\Domains\User\Contracts;


interface IUpdateUserPassword
{
    /**
     * Update user password
     *
     * @param array $params
     * @return mixed
     */
    public function updatePassword(array $params);
}
