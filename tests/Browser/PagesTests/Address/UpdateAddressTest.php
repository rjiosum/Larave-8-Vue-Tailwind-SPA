<?php

namespace Tests\Browser\PagesTests\Address;


use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Address\UpdateAddress;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class UpdateAddressTest extends DuskTestCase
{
    use WithFaker, Authenticate;

    private $user;
    private $country;
    private $address;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class UserSeeder');
        $this->artisan('db:seed --class CountrySeeder');
        $this->artisan('db:seed --class AddressSeeder');

        $this->user = User::find(1);
        $this->country = Country::find(1);
        $this->address = Address::where('user_id', '=', $this->user->id)->orderByDesc('id')->first();
    }

    /** @test */
    public function canAddAnAddress(): void
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->visit(new UpdateAddress($this->address->uuid))
                ->pause(5000)
                ->assertTitle('Update Address')
                ->waitForText('Update Your Address')
                ->screenshot('Address/UpdateAddressTest/01-update-address-form')
                ->updateAddress([
                    'postcode' => $this->faker->postcode,
                    'address_1' => $this->faker->streetName,
                    'address_2' => $this->faker->secondaryAddress,
                    'town' => $this->faker->city,
                    'county' => $this->faker->city,
                    'country_id' => $this->country->id
                ])
                ->pause(5000)
                ->screenshot('Address/UpdateAddressTest/02-address-updated')
                ->waitForText(trans('response.success.update', ['attribute' => 'Address']), 5)
                ->assertSee(trans('response.success.update', ['attribute' => 'Address']))
                ->screenshot('Address/UpdateAddressTest/03-address-update-success');
        });
    }
}
