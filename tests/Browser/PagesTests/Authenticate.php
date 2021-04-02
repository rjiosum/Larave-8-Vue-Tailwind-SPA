<?php


namespace Tests\Browser\PagesTests;


use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Auth\Login;
use Tests\Browser\Pages\User\Dashboard;

trait Authenticate
{
    public function auth(Browser $browser)
    {
        $browser->visit(new Login)
            ->pause(5000)
            ->submit($this->user->email, 'password')
            ->on(new Dashboard)
            ->pause(4000);

        return $browser;
    }
}
