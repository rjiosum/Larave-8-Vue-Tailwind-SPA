<?php

namespace Tests\Browser\PagesTests\User;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class DashboardTest extends DuskTestCase
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
    public function canVisitUserDashboard(): void
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->assertTitle('Dashboard')
                ->assertSeeLink('Profile')
                ->assertSeeLink('Password')
                ->assertSeeLink('Avatar')
                ->assertSeeLink('Articles')
                ->screenshot('User/DashboardTest/01-user-dashboard');
        });
    }
}
