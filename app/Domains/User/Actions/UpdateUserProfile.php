<?php


namespace App\Domains\User\Actions;


use App\Models\User;
use App\Domains\User\Contracts\IUpdateUserProfile;
use Illuminate\Support\Facades\DB;

class UpdateUserProfile implements IUpdateUserProfile
{
    /**
     * @var User
     */
    private $user;

    /**
     * UpdateUserProfile constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Update users profile.
     *
     * @param array $params
     * @return mixed
     */
    public function updateProfile(array $params)
    {
        return DB::transaction(function () use (&$params) {
            return $this->user
                ->where('id', $params['id'])
                ->update([
                    'first_name' => $params['first_name'],
                    'last_name' => $params['last_name']
                ]);
        }, 5);
    }
}
