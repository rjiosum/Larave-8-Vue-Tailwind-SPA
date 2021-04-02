<?php

namespace Tests\Browser\PagesTests\Article;

use App\Models\Article;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Article\ViewArticle;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class ViewArticleTest extends DuskTestCase
{
    use Authenticate;

    private $user;
    private $article;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->user = User::find(1);
        $this->article = Article::where('user_id', '=', $this->user->id)->orderByDesc('id')->first();
    }

    /** @test */
    public function canViewAnArticle()
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->visit(new ViewArticle($this->article->slug))
                ->waitForText($this->article->title)
                ->assertSee($this->article->title)
                ->pause(1000)
                ->screenshot('Article/ViewArticleTest/01-view-article');
        });
    }
}
