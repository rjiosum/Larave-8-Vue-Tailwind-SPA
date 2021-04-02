<?php


namespace App\Domains\Article\Actions;


use App\Models\Article;
use App\Domains\Article\Contracts\IFindArticle;

class FindArticle implements IFindArticle
{
    /**
     * @var Article
     */
    private $article;

    /**
     * FindArticle constructor.
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Fetch the specified resource.
     *
     * @param int $id
     * @return mixed
     */
    public function findArticleById(int $id)
    {
        return $this->article::with('user')->findOrFail($id);
    }

    /**
     * Fetch the specified resource.
     *
     * @param $uuid
     * @return mixed
     */
    public function findArticleByUuid($uuid)
    {
        return $this->article::with('user')->where('uuid', '=', $uuid)->firstOrFail();
    }

    /**
     * Fetch the specified resource.
     *
     * @param string $slug
     * @return mixed
     */
    public function findArticleBySlug(string $slug)
    {
        return $this->article::with('user')->where('slug', '=', $slug)->firstOrFail();
    }
}
