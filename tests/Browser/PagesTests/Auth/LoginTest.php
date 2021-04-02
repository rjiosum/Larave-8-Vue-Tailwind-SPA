<?php

namespace Tests\Browser\PagesTests\Auth;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Auth\Login;
use Tests\Browser\Pages\User\Dashboard;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create(User::class);
    }

    /** @test */
    public function canLoginWithValidCredentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
                ->pause(5000)
                ->assertTitle('Login')
                ->assertSeeLink('Sign Up')
                ->assertSeeLink('Forgot Password?')
                ->screenshot('Auth/LoginTest/01-form')
                ->submit($this->user->email, 'password')
                ->screenshot('Auth/LoginTest/02-form-submitted')
                ->pause(5000)
                ->on(new Dashboard)
                ->assertSee('Share')
                ->assertSee('Profile')
                ->assertSee('Password')
                ->assertSee('Avatar')
                ->screenshot('Auth/LoginTest/03-login-success-dashboard')
                ->assertHasCookie(config('passport.cookie.name'), false);
        });
    }

    /** @test */
    public function cannotLoginWithInvalidCredentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
                ->pause(5000)
                ->submit($this->user->email, 'wrong-password')
                ->pause(5000)
                ->waitForText(trans('auth.failed'), 5)
                ->assertSee(trans('auth.failed'))
                ->screenshot('Auth/LoginTest/04-invalid-credentials');
        });
    }

}
