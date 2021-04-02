<?php

namespace Tests\Browser\PagesTests\Address;


use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Address\CreateAddress;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class CreateAddressTest extends DuskTestCase
{
    use WithFaker, Authenticate;

    private $user;
    private $country;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class UserSeeder');
        $this->artisan('db:seed --class CountrySeeder');
        $this->user = User::find(1);
        $this->country = Country::find(1);
    }

    /** @test */
    public function canAddAnAddress(): void
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->visit(new CreateAddress)
                ->pause(5000)
                ->assertTitle('Create Address')
                ->waitForText('Add Your Address')
                ->screenshot('Address/CreateAddressTest/01-create-address-form')
                ->createAddress([
                    'postcode' => $this->faker->postcode,
                    'address_1' => $this->faker->streetName,
                    'address_2' => $this->faker->secondaryAddress,
                    'town' => $this->faker->city,
                    'county' => $this->faker->city,
                    'country_id' => $this->country->id
                ])
                ->pause(5000)
                ->screenshot('Address/CreateAddressTest/02-address-created')
                ->waitForText(trans('response.success.create', ['attribute' => 'Address']), 5)
                ->assertSee(trans('response.success.create', ['attribute' => 'Address']))
                ->screenshot('Address/CreateAddressTest/03-address-created-success');
        });
    }
}
