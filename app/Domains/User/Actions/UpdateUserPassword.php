<?php


namespace App\Domains\User\Actions;


use App\Models\User;
use App\Domains\User\Contracts\IUpdateUserPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserPassword implements IUpdateUserPassword
{
    /**
     * @var User
     */
    private $user;

    /**
     * UpdateUserPassword constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Update user password
     *
     * @param array $params
     * @return mixed
     */
    public function updatePassword(array $params)
    {
        return DB::transaction(function () use(&$params){
            return $this->user
                ->where('id', $params['id'])
                ->update([
                    'password' => Hash::make($params['password'])
                ]);
        }, 5);
    }
}
