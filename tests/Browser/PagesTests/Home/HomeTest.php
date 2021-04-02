<?php

namespace Tests\Browser\PagesTests\Home;

use App\Models\Article;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Home\Home;
use Tests\DuskTestCase;

class HomeTest extends DuskTestCase
{
    private $article;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->article = Article::orderByDesc('id')->first();
    }

    /** @test */
    public function canVisitHomePage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Home)
                ->waitForText($this->article->title)
                ->assertSeeLink('Create')
                ->assertSee($this->article->title)
                ->screenshot('Home/01-home');
        });
    }
}
