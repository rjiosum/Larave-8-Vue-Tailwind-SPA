<?php


namespace App\Domains\Article\Contracts;


interface ICreateArticle
{
    /**
     * Store a newly created resource in storage.
     *
     * @param array $params
     * @return mixed
     */
    public function createArticle(array $params);
}
