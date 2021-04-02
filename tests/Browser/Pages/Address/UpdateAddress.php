<?php

namespace Tests\Browser\Pages\Address;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;


class UpdateAddress extends Page
{
    private $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/user/address/' . $this->uuid . '/edit';
    }

    public function updateAddress(Browser $browser, array $data = [])
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
