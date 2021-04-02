<?php


namespace App\Domains\User\Contracts;


interface IUpdateUserProfile
{
    /**
     * Update users profile.
     *
     * @param array $params
     * @return mixed
     */
    public function updateProfile(array $params);
}
