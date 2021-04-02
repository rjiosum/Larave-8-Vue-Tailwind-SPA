<?php

namespace App\Providers\Domains;

use App\Domains\User\Actions\{FindUser, UpdateUserAvatar, UpdateUserPassword, UpdateUserProfile};
use App\Domains\User\Contracts\{IFindUser, IUpdateUserAvatar, IUpdateUserPassword, IUpdateUserProfile};
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    private $actions = [
        IUpdateUserProfile::class => UpdateUserProfile::class,
        IUpdateUserPassword::class => UpdateUserPassword::class,
        IUpdateUserAvatar::class => UpdateUserAvatar::class,
        IFindUser::class => FindUser::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->actions as $interface=>$implementation){
            $this->app->bind($interface, $implementation);
        }
    }
}
