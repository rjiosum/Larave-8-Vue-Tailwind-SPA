<?php

namespace Tests\Browser\Pages\User;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class Password extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/user/password';
    }

    /**
     * @param Browser $browser
     * @param $password
     * @param $password_confirmation
     */
    public function updatePassword(Browser $browser, $password, $password_confirmation): void
    {
        $browser->typeSlowly('password', $password)
            ->typeSlowly('password_confirmation', $password_confirmation)
            ->press('UPDATE');
    }
}
