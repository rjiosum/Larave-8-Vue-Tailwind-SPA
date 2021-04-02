<?php


namespace App\Domains\Article\Contracts;


interface IFetchArticle
{
    /**
     * Fetch a listing of the user resource.
     *
     * @param int $perPage
     * @param string $sortBy
     * @param string $sortDirection
     * @return mixed
     */
    public function fetchUserArticles(int $perPage = 10, string $sortBy = 'id', string $sortDirection = 'desc');

    /**
     * Fetch a listing of the resource.
     *
     * @param int $perPage
     * @param string $sortBy
     * @param string $sortDirection
     * @return mixed
     */
    public function fetchArticles(int $perPage = 10, string $sortBy = 'id', string $sortDirection = 'desc');
}
