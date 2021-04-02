<?php


namespace App\Domains\Article\Contracts;


interface IUpdateArticle
{
    /**
     * Update the specified resource in storage.
     *
     * @param array $params
     * @return mixed
     */
    public function updateArticle(array $params);
}
