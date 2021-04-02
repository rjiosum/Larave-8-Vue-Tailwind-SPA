<?php

namespace Tests\Browser\PagesTests\User;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\User\Profile;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class ProfileTest extends DuskTestCase
{
    use Authenticate, WithFaker;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class UserSeeder');
        $this->user = User::find(1);
    }

    /** @test */
    public function canUpdateProfile(): void
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->assertSeeLink('Profile')
                ->clickLink('Profile')
                ->on(new Profile)
                ->waitForText('Update your account details')
                ->assertTitle('Update Profile')
                ->screenshot('User/ProfileTest/01-profile-form')
                ->updateProfile($this->faker->firstName, $this->faker->lastName)
                ->screenshot('User/ProfileTest/02-profile-updated')
                ->waitForText(trans('response.success.update', ['attribute' => 'Profile']), 5)
                ->assertSee(trans('response.success.update', ['attribute' => 'Profile']))
                ->screenshot('User/ProfileTest/03-profile-update-success');
        });
    }
}
