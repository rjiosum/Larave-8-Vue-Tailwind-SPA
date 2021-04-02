<?php

namespace Tests\Browser\Pages\Auth;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class Register extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/register';
    }

    /**
     * @param Browser $browser
     * @param array $data
     */
    public function submit(Browser $browser, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $browser->type($key, $value);
        }
        $browser->press('SIGN UP');
    }

}
