<?php


namespace App\Domains\Article\Contracts;


interface IFindArticle
{
    /**
     * Fetch the specified resource.
     *
     * @param int $id
     * @return mixed
     */
    public function findArticleById(int $id);

    /**
     * Fetch the specified resource.
     *
     * @param $uuid
     * @return mixed
     */
    public function findArticleByUuid($uuid);

    /**
     * Fetch the specified resource.
     *
     * @param string $slug
     * @return mixed
     */
    public function findArticleBySlug(string $slug);
}
