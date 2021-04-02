<?php

namespace Tests\Browser\Pages\Article;

use Tests\Browser\Pages\Page;

class ViewArticle extends Page
{
    private $slug;

    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return '/user/article/' . $this->slug . '/view';
    }
}
