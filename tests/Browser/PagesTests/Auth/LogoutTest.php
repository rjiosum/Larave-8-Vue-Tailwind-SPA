<?php

namespace Tests\Browser\PagesTests\Auth;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Auth\Login;
use Tests\Browser\Pages\User\Dashboard;
use Tests\DuskTestCase;

class LogoutTest extends DuskTestCase
{
    /** @test */
    public function canLogOutUser()
    {
        $user = $this->create(User::class);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new Login)
                ->pause(5000)
                ->submit($user->email, 'password')
                ->on(new Dashboard)
                ->screenshot('Auth/LogoutTest/01-login-success')
                ->pause(5000)
                ->clickLogoutLink()
                ->pause(5000)
                ->on(new Login)
                ->pause(5000)
                ->assertTitle('Login')
                ->assertSeeLink('Sign Up')
                ->assertSeeLink('Forgot Password?')
                ->assertCookieMissing(config('passport.cookie.name'), false)
                ->screenshot('Auth/LogoutTest/03-logout-success');
        });
    }
}
