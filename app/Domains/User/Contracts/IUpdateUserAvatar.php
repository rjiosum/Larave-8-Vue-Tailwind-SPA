<?php


namespace App\Domains\User\Contracts;


use App\Models\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface IUpdateUserAvatar
{
    /**
     * Update avatar filename in the resource.
     *
     * @param array $params
     * @return mixed
     */
    public function updateAvatar(array $params);

    /**
     * Store user avatar image on the disk.
     *
     * @param UploadedFile $uploadedFile
     * @param User $user
     * @param string $disk
     * @return string
     */
    public function storeAvatarImage(UploadedFile $uploadedFile, User $user, string $disk = 'public'): string;
}
