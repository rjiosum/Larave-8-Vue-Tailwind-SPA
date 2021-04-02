<?php

namespace Tests\Browser\PagesTests\Article;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Article\CreateArticle;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class CreateArticleTest extends DuskTestCase
{
    use Authenticate, WithFaker;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class UserSeeder');
        $this->user = User::find(1);
    }

    /** @test */
    public function canCreateAnArticle()
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->visit(new CreateArticle)
                ->waitForText('Write / Share an article!')
                ->assertTitle('Create Article')
                ->waitFor('.ck-editor__main > .ck-editor__editable')
                ->screenshot('Article/CreateArticleTest/01-create-article-form')
                ->createArticle($this->faker->unique()->sentence, $this->faker->realText(100))
                ->screenshot('Article/CreateArticleTest/02-article-created')
                ->waitForText(trans('response.success.create', ['attribute' => 'Article']), 5)
                ->assertSee(trans('response.success.create', ['attribute' => 'Article']))
                ->screenshot('Article/CreateArticleTest/03-article-created-success');
        });
    }
}
