<?php


namespace App\Domains\Article\Actions;


use App\Models\Article;
use App\Models\User;
use App\Domains\Article\Contracts\IFetchArticle;


class FetchArticle implements IFetchArticle
{
    /**
     * @var Article
     */
    private $article;
    /**
     * @var User
     */
    private $user;

    /**
     * FetchArticle constructor.
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
        $this->user = auth()->guard('api')->user();
    }

    /**
     * Fetch a listing of the resource.
     *
     * @param int $perPage
     * @param string $sortBy
     * @param string $sortDirection
     * @return mixed
     */
    public function fetchArticles(int $perPage = 12, string $sortBy = 'id', string $sortDirection = 'desc')
    {
        return $this->article::with('user')->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }

    /**
     * Fetch a listing of the user resource.
     *
     * @param int $perPage
     * @param string $sortBy
     * @param string $sortDirection
     * @return mixed
     */
    public function fetchUserArticles(int $perPage = 12, string $sortBy = 'id', string $sortDirection = 'desc')
    {
        return $this->user->articles()->with('user')->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
}
