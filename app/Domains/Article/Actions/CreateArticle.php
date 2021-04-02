<?php


namespace App\Domains\Article\Actions;


use App\Models\Article;
use App\Models\User;
use App\Domains\Article\Contracts\ICreateArticle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateArticle implements ICreateArticle
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
     * CreateArticle constructor.
     * @param Article $article
     *
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
        $this->user = auth()->guard('api')->user();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $params
     * @return mixed
     */
    public function createArticle(array $params)
    {
        $collection = collect($params);
        $merged = $collection->merge(['slug' => '']);

        return DB::transaction(function () use (&$merged) {
            $article = $this->user->articles()->create($merged->all());
            $article->slug = Str::slug($article->id . '-' . $article->title);
            $article->save();
            return $article;
        }, 5);
    }
}
