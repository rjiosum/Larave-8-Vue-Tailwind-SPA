<?php

namespace Tests\Browser\Pages\Address;

use Tests\Browser\Pages\Page;


class AddressLists extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/user/addresses';
    }
}
