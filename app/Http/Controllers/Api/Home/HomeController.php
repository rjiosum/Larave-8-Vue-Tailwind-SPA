<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollectionResource;
use App\Http\Resources\ArticleResource;
use App\Domains\Article\Contracts\IFetchArticle;
use App\Domains\Article\Contracts\IFindArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class HomeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param IFetchArticle $fetch
     * @return ArticleCollectionResource
     */
    public function index(Request $request, IFetchArticle $fetch)
    {
        $perPage = $request->get('per_page') ?? 12;
        return new ArticleCollectionResource($fetch->fetchArticles($perPage));
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @param IFindArticle $find
     * @return JsonResponse
     */
    public function show(string $slug, IFindArticle $find)
    {
        $article = $find->findArticleBySlug($slug);
        return $this->responseJson(true, new ArticleResource($article), '', Response::HTTP_OK);
    }
}
