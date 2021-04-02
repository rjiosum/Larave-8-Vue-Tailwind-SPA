<?php

namespace Tests\Browser\PagesTests\Address;

use App\Models\Address;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Address\AddressLists;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class DeleteAddressTest extends DuskTestCase
{
    use Authenticate;

    private $user;
    private $address;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class UserSeeder');
        $this->artisan('db:seed --class CountrySeeder');
        $this->artisan('db:seed --class AddressSeeder');
        $this->user = User::find(1);
        $this->address = Address::where('user_id', '=', $this->user->id)->orderByDesc('id')->first();
    }

    /** @test */
    public function canDeleteAnAddress(): void
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->assertSeeLink('Address')
                ->clickLink('Address')
                ->pause(5000)
                ->on(new AddressLists)
                ->waitForText($this->address->address_1)
                ->assertSee($this->address->address_1)
                ->screenshot('Address/DeleteAddressTest/01-address-list')
                ->click('@' . $this->address->uuid)
                ->screenshot('Address/DeleteAddressTest/02-delete-btn-clicked')
                ->pause(5000)
                ->press('Yes')
                ->pause(5000)
                ->screenshot('Address/DeleteAddressTest/03-yes-btn-pressed')
                ->pause(5000)
                ->assertDontSee($this->address->title)
                ->screenshot('Address/DeleteAddressTest/04-address-deleted');
        });
    }
}
