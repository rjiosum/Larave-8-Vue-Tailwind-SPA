<?php


namespace App\Providers\Domains;


use App\Domains\Address\Actions\{CreateAddress, DeleteAddress, FetchAddress, FindAddress, UpdateAddress};
use App\Domains\Address\Contracts\{ICreateAddress, IDeleteAddress, IFetchAddress, IFindAddress, IUpdateAddress};
use Illuminate\Support\ServiceProvider;

class AddressServiceProvider extends ServiceProvider
{
    protected $actions = [
        ICreateAddress::class => CreateAddress::class,
        IFetchAddress::class => FetchAddress::class,
        IFindAddress::class => FindAddress::class,
        IUpdateAddress::class => UpdateAddress::class,
        IDeleteAddress::class => DeleteAddress::class,
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
        foreach ($this->actions as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}
