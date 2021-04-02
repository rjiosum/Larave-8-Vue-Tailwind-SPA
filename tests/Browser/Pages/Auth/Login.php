<?php

namespace Tests\Browser\Pages\Auth;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class Login extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/login';
    }

    /**
     * @param Browser $browser
     * @param $email
     * @param $password
     */
    public function submit(Browser $browser, $email, $password): void
    {
        $browser->type('email', $email)
            ->type('password', $password)
            ->press('LOGIN');
    }
}
