<?php

namespace Tests\Browser\PagesTests\User;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\User\Avatar;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class AvatarTest extends DuskTestCase
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
    public function canUpdateAvatar(): void
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->assertSeeLink('Avatar')
                ->clickLink('Avatar')
                ->pause(5000)
                ->on(new Avatar)
                ->assertTitle('Change Avatar')
                ->screenshot('User/AvatarTest/01-avatar-form')
                ->updateAvatar()
                ->screenshot('User/AvatarTest/02-avatar-updated')
                ->waitForText(trans('response.success.update', ['attribute' => 'Avatar']), 5)
                ->assertSee(trans('response.success.update', ['attribute' => 'Avatar']))
                ->screenshot('User/AvatarTest/03-avatar-update-success');
        });
    }
}
