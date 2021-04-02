<?php


namespace App\Domains\Article\Contracts;


interface IDeleteArticle
{

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteArticle(int $id);
}
