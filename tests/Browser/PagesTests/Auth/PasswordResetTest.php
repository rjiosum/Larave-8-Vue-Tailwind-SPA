<?php

namespace Tests\Browser\PagesTests\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Auth\PasswordReset;
use Tests\DuskTestCase;

class PasswordResetTest extends DuskTestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create(User::class);
    }

    /** @test */
    public function canResetPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new PasswordReset($this->token()))
                ->waitForText('Reset your password.')
                ->screenshot('Auth/PasswordResetTest/01-reset-form')
                ->submit($this->user->email, 'new-password', 'new-password')
                ->screenshot('Auth/PasswordResetTest/02-reset-form-submitted')
                ->waitForText(trans('passwords.reset'), 5)
                ->assertSee(trans('passwords.reset'))
                ->pause(1000)
                ->screenshot('Auth/PasswordResetTest/03-reset-success');
        });
    }

    private function token()
    {
        return Password::broker()->createToken($this->user);
    }
}
