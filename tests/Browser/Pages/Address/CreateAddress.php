<?php

namespace Tests\Browser\Pages\Address;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;


class CreateAddress extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/user/address/create';
    }

    public function createAddress(Browser $browser, array $data = [])
    {
        foreach ($data as $key=>$value){
            if($key == 'country_id'){
                $browser->select($key, $value);
            } else {
                $browser->type($key, $value);
            }
        }
        $browser->press('SAVE');
    }

}
