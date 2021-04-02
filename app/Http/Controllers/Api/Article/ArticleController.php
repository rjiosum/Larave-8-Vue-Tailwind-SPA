<?php

namespace App\Http\Controllers\Api\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\{ArticleStoreRequest, ArticleUpdateRequest};
use App\Http\Resources\{ArticleCollectionResource, ArticleResource};
use App\Domains\Article\Contracts\{ICreateArticle, IDeleteArticle, IFetchArticle, IFindArticle, IUpdateArticle};
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\{JsonResponse, Request, Response};

class ArticleController extends Controller
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

        return new ArticleCollectionResource($fetch->fetchUserArticles($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleStoreRequest $request
     * @param ICreateArticle $create
     * @return JsonResponse
     */
    public function store(ArticleStoreRequest $request, ICreateArticle $create)
    {
        $article = $create->createArticle($request->all());

        return $this->responseJson(
            (bool)$article,
            new ArticleResource($article),
            (bool)$article
                ? trans('response.success.create', ['attribute' => 'Article'])
                : trans('response.error.create', ['attribute' => 'article']),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @param IFindArticle $find
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(string $slug, IFindArticle $find)
    {
        $article = $find->findArticleBySlug($slug);

        $this->authorize('view', $article);

        return $this->responseJson(true, new ArticleResource($article), '', Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleUpdateRequest $request
     * @param string $uuid
     * @param IFindArticle $find
     * @param IUpdateArticle $update
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(ArticleUpdateRequest $request,
                           string $uuid,
                           IFindArticle $find,
                           IUpdateArticle $update)
    {
        $article = $find->findArticleByUuid($uuid);
        $this->authorize('update', $article);

        if($request->has('check') && $request->check){
            return $this->responseJson(true, new ArticleResource($article), '', Response::HTTP_OK);
        }

        $request->merge(['id' => $article->id]);
        $status = $update->updateArticle($request->all());

        return $this->responseJson(
            (bool)$status,
            new ArticleResource($article->fresh()),
            (bool)$status
                ? trans('response.success.update', ['attribute' => 'Article'])
                : trans('response.error.update', ['attribute' => 'article']),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $uuid
     * @param IFindArticle $find
     * @param IDeleteArticle $delete
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(string $uuid, IFindArticle $find, IDeleteArticle $delete)
    {
        $article = $find->findArticleByUuid($uuid);
        $this->authorize('delete', $article);

        $status = $delete->deleteArticle($article->id);

        return $this->responseJson(
            (bool)$status,
            [],
            (bool)$status
                ? trans('response.success.delete', ['attribute' => 'Article'])
                : trans('response.error.delete', ['attribute' => 'article']),
            Response::HTTP_OK
        );

    }
}
