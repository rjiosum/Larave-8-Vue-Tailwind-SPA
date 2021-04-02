<?php

namespace Tests\Browser\Pages\Auth;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class ForgotPassword extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/forgot-password';
    }

    /**
     * @param Browser $browser
     * @param $email
     */
    public function submit(Browser $browser, $email): void
    {
        $browser->type('email', $email)
            ->press('CONTINUE');
    }
}
