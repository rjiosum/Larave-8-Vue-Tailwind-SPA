<?php

namespace Tests\Browser\Pages\Auth;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class PasswordReset extends Page
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/password-reset/'.$this->token;
    }

    /**
     * @param Browser $browser
     * @param $email
     * @param $password
     * @param $password_confirmation
     */
    public function submit(Browser $browser, $email, $password, $password_confirmation): void
    {
        $browser->type('email', $email)
            ->type('password', $password)
            ->type('password_confirmation', $password_confirmation)
            ->press('RESET');
    }
}
