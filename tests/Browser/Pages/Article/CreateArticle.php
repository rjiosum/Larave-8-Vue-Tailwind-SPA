<?php

namespace Tests\Browser\Pages\Article;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class CreateArticle extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/user/article/create';
    }

    /**
     * @param Browser $browser
     * @param $title
     * @param $description
     */
    public function createArticle(Browser $browser, $title, $description): void
    {
        $browser->type('title', $title)
            ->type('.ck-editor__main > .ck-editor__editable', $description)
            ->check('status')
            ->press('SAVE');
    }
}
