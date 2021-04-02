<?php

namespace App\Providers\Domains;

use App\Domains\Article\Actions\{CreateArticle, DeleteArticle, FetchArticle, FindArticle, UpdateArticle};
use App\Domains\Article\Contracts\{ICreateArticle, IDeleteArticle, IFetchArticle, IFindArticle, IUpdateArticle};
use Illuminate\Support\ServiceProvider;

class ArticleServiceProvider extends ServiceProvider
{
    protected $actions = [
        ICreateArticle::class => CreateArticle::class,
        IFetchArticle::class => FetchArticle::class,
        IFindArticle::class => FindArticle::class,
        IUpdateArticle::class => UpdateArticle::class,
        IDeleteArticle::class => DeleteArticle::class,
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->actions as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}
