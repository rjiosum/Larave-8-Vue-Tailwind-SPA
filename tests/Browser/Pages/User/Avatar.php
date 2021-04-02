<?php

namespace Tests\Browser\Pages\User;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class Avatar extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/user/avatar';
    }

    /**
     * @param Browser $browser
     */
    public function updateAvatar(Browser $browser): void
    {
        $browser->attach('avatar', 'storage/app/public/avatars/avatar.png')
            ->press('UPDATE');
    }
}
