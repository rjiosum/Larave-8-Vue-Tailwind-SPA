<?php

namespace Tests\Browser\PagesTests\User;


use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\User\Password;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class PasswordTest extends DuskTestCase
{
    use Authenticate;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class UserSeeder');
        $this->user = User::find(1);
    }

    /** @test */
    public function canUpdatePassword(): void
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->assertSeeLink('Password')
                ->clickLink('Password')
                ->pause(5000)
                ->on(new Password)
                ->assertTitle('Change Password')
                ->screenshot('User/PasswordTest/01-password-form')
                ->updatePassword('new-password', 'new-password')
                ->screenshot('User/PasswordTest/02-password-updated')
                ->waitForText(trans('response.success.update', ['attribute' => 'Password']), 5)
                ->assertSee(trans('response.success.update', ['attribute' => 'Password']))
                ->screenshot('User/PasswordTest/03-password-update-success');
        });
    }
}
