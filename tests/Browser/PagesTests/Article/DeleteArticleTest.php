<?php

namespace Tests\Browser\PagesTests\Article;

use App\Models\Article;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Article\ArticleLists;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class DeleteArticleTest extends DuskTestCase
{
    use Authenticate;

    private $user;
    private $article;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->user = User::find(1);
        $this->article = Article::where('user_id', '=', $this->user->id)->orderByDesc('id')->first();
    }

    /** @test */
    public function canDeleteAnArticle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->assertSeeLink('Articles')
                ->clickLink('Articles')
                ->pause(5000)
                ->on(new ArticleLists)
                ->waitForText($this->article->title)
                ->assertSee($this->article->title)
                ->screenshot('Article/DeleteArticleTest/01-article-list')
                ->click('@' . $this->article->uuid)
                ->screenshot('Article/DeleteArticleTest/02-delete-btn-clicked')
                ->pause(5000)
                ->press('Yes')
                ->pause(5000)
                ->screenshot('Article/DeleteArticleTest/03-yes-btn-pressed')
                //->refresh()
                ->pause(5000)
                ->assertDontSee($this->article->title)
                ->screenshot('Article/DeleteArticleTest/04-article-deleted');
        });
    }
}
