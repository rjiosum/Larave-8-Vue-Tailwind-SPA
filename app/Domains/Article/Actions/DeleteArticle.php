<?php


namespace App\Domains\Article\Actions;


use App\Models\Article;
use App\Domains\Article\Contracts\IDeleteArticle;
use Illuminate\Support\Facades\DB;

class DeleteArticle implements IDeleteArticle
{
    /**
     * @var Article
     */
    private $article;

    /**
     * DeleteArticle constructor.
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteArticle(int $id)
    {
        return DB::transaction(function () use (&$id) {
            return $this->article->where('id', $id)->delete();
        }, 5);
    }
}
