<?php

namespace Tests\Browser\Pages\Article;

use Tests\Browser\Pages\Page;


class ArticleLists extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/user/articles';
    }
}
