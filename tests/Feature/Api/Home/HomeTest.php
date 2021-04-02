<?php

namespace Tests\Feature\Api\Home;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canListCollectionOfPaginatedArticleResultsWithoutUserLoggedIn(): void
    {
        $this->create(User::class, [], 2);
        $this->create(Article::class, [], 50);

        $this->getJson(route('home'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'title',
                            'slug',
                            'description',
                            'status',
                            'user' => [
                                'data' => [
                                    'type',
                                    'id',
                                    'attributes' => [
                                        'first_name',
                                        'last_name',
                                        'name',
                                        'email',
                                        'avatar',
                                    ],
                                    'link' => [
                                        'self',
                                    ],
                                ],
                            ],
                            'created_at',
                            'updated_at',
                            'created_h',
                            'updated_h',
                        ],
                        'link' => [
                            'self',
                        ],
                    ],
                ],
                'links' => [
                    'self',
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'options',
                    'current_page',
                    'from',
                    'last_page',
                    'links',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ],
            ]);
    }

    /** @test */
    public function willThrow404ErrorIfArticleDoesNotExistsWithoutUserLoggedIn(): void
    {
        $this->getJson(route('home.show', ['slug' => 'some-random-slug']))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function canShowAnArticleWithoutUserLoggedIn(): void
    {
        $user = $this->create(User::class);
        $article = $this->create(Article::class);

        $this->getJson(route('home.show', ['slug' => $article->slug]))
            ->assertJson([
                'status' => true,
                'message' => '',
                'data' => [
                    'type' => 'article',
                    'id' => $article->uuid,
                    'attributes' => [
                        'title' => $article->title,
                        'slug' => $article->slug,
                        'status' => $article->status,
                        'user' => [
                            'data' => [
                                'type' => 'user',
                                'id' => $user->uuid,
                                'attributes' => [
                                    'first_name' => $user->first_name,
                                    'last_name' => $user->last_name,
                                    'name' => $user->first_name . ' ' . $user->last_name,
                                    'email' => $user->email,
                                ],
                                'link' => [
                                    'self' => route('user.profile'),
                                ],
                            ],
                        ],
                        'created_at' => $article->created_at->format('d-m-Y H:i:s'),
                        'updated_at' => $article->updated_at->format('d-m-Y H:i:s'),
                        'created_h' => $article->created_at->diffForHumans(),
                        'updated_h' => $article->updated_at->diffForHumans(),
                    ],
                    'link' => [
                        'self' => route('article.show', ['slug' => $article->slug]),
                    ],
                ],
            ])
            ->assertStatus(Response::HTTP_OK);
    }
}
