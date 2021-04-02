<?php


namespace App\Domains\Article\Actions;


use App\Models\Article;
use App\Domains\Article\Contracts\IUpdateArticle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateArticle implements IUpdateArticle
{
    /**
     * @var Article
     */
    private $article;

    /**
     * UpdateArticle constructor.
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $params
     * @return mixed
     */
    public function updateArticle(array $params)
    {
        $collection = collect($params);
        $slug = Str::slug($collection->get('id') . '-' . $collection->get('title'));
        $merged = $collection->merge(['slug'=>$slug]);

        return DB::transaction(function () use (&$merged) {
            return $this->article
                ->where('id', $merged->get('id'))
                ->update($merged->except(['id', 'uuid'])->all());
        }, 5);
    }
}
