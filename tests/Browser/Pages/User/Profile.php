<?php

namespace Tests\Browser\Pages\User;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class Profile extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/user/profile';
    }

    /**
     * @param Browser $browser
     * @param $first_name
     * @param $last_name
     */
    public function updateProfile(Browser $browser, $first_name, $last_name): void
    {

        $browser->clear('input[name=first_name]')
            ->typeSlowly('first_name', $first_name)
            ->clear('input[name=last_name]')
            ->typeSlowly('last_name', $last_name)
            ->press('UPDATE');
    }
}
