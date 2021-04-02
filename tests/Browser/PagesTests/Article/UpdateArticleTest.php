<?php

namespace Tests\Browser\PagesTests\Article;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Article\UpdateArticle;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class UpdateArticleTest extends DuskTestCase
{
    use Authenticate, WithFaker;

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
    public function canUpdateAnArticle()
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->visit(new UpdateArticle($this->article->uuid))
                ->waitForText('Update Your Article')
                ->assertTitle('Update Article')
                ->waitFor('.ck-editor__main > .ck-editor__editable')
                ->screenshot('Article/UpdateArticleTest/01-update-article-form')
                ->updateArticle($this->faker->unique()->sentence, $this->faker->realText(100))
                ->screenshot('Article/UpdateArticleTest/02-article-updated')
                ->waitForText(trans('response.success.update', ['attribute' => 'Article']), 5)
                ->assertSee(trans('response.success.update', ['attribute' => 'Article']))
                ->screenshot('Article/UpdateArticleTest/03-article-update-success');
        });
    }
}
