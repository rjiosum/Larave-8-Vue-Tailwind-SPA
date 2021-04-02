<?php

namespace Tests\Browser\Pages\Article;

use Tests\Browser\Pages\Page;
use Laravel\Dusk\Browser;

class UpdateArticle extends Page
{

    private $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/user/article/' . $this->uuid . '/edit';
    }

    /**
     * @param Browser $browser
     * @param $title
     * @param $description
     */
    public function updateArticle(Browser $browser, $title, $description): void
    {
        $browser->clear("input[name='title']")
            ->type('title', $title)
            ->type('.ck-editor__main > .ck-editor__editable', $description)
            ->press('SAVE');
    }
}
