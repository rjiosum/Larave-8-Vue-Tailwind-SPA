<?php

namespace Tests\Browser\PagesTests\Auth;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Auth\ForgotPassword;
use Tests\DuskTestCase;

class ForgotPasswordTest extends DuskTestCase
{
    /** @test */
    public function canSendPasswordResetEmail(): void
    {
        $user = $this->create(User::class);

        $this->browse(function(Browser $browser) use ($user){
            $browser->visit(new ForgotPassword)
                ->pause(5000)
                ->assertTitle('Forgot Password')
                ->assertSeeLink('Sign In')
                ->screenshot('Auth/ForgotPasswordTest/01-form')
                ->submit($user->email)
                ->screenshot('Auth/ForgotPasswordTest/02-form-submitted')
                ->pause(5000)
                ->waitForText(trans('passwords.sent'))
                ->assertSee(trans('passwords.sent'))
                ->screenshot('Auth/ForgotPasswordTest/03-email-sent');
        });
    }
}
