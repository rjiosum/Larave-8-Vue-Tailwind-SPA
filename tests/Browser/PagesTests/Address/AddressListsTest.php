<?php

namespace Tests\Browser\PagesTests\Address;

use App\Models\Address;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Address\AddressLists;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class AddressListsTest extends DuskTestCase
{
    use Authenticate;

    private $user;
    private $address;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class UserSeeder');
        $this->artisan('db:seed --class CountrySeeder');
        $this->artisan('db:seed --class AddressSeeder');
        $this->user = User::find(1);
        $this->address = Address::where('user_id', '=', $this->user->id)->orderByDesc('id')->first();
    }

    /** @test */
    public function canShowListOfPaginatedResult()
    {
        $this->browse(function (Browser $browser){
           $this->auth($browser)
               ->assertSeeLink('Addresses')
               ->clickLink('Addresses')
               ->pause(5000)
               ->on(new AddressLists)
               ->pause(5000)
               ->assertTitle('Addresses')
               ->waitForText($this->address->address_1)
               ->assertSeeLink('Add New Address')
               ->assertSee($this->address->address_1)
               ->screenshot('Address/AddressListTest/01-address-lists');
        });
    }

}
