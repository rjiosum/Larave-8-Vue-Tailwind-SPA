<?php

namespace Tests\Browser\Pages\Home;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class Home extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
    }
}
