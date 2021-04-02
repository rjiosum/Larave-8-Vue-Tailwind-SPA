<?php

namespace Tests\Feature\Api\Article;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $user, $loggedInUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create(User::class);
        $this->loggedInUser = $this->ActingAs($this->user, 'api');
    }

    /** @test */
    public function canListCollectionOfPaginatedArticleResults(): void
    {
        $this->create(Article::class, [], 30);

        $this->loggedInUser->getJson(route('article'))
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
    public function willThrow404ErrorIfArticleDoesNotExists(): void
    {
        $this->loggedInUser->getJson(route('article.show', ['slug' => 'some-random-slug']))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function canShowAnArticle(): void
    {
        $article = $this->create(Article::class);

        $this->loggedInUser->getJson(route('article.show', ['slug' => $article->slug]))
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
                                'id' => $this->user->uuid,
                                'attributes' => [
                                    'first_name' => $this->user->first_name,
                                    'last_name' => $this->user->last_name,
                                    'name' => $this->user->first_name . ' ' . $this->user->last_name,
                                    'email' => $this->user->email,
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

    /** @test */
    public function willThrowValidationErrorWhileCreatingAnArticleWithWrongInput(): void
    {
        //title field is required
        $this->loggedInUser->postJson(route('article.store'), $this->data(['title' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => [
                        trans('validation.required', ['attribute' => 'title'])
                    ]
                ]
            ]);

        //title can have max 150 character
        $this->loggedInUser->postJson(route('article.store'), $this->data(['title' => Str::random(200)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => [
                        trans('validation.max.string', ['attribute' => 'title', 'max' => 150])
                    ]
                ]
            ]);

        //description field is required
        $this->loggedInUser->postJson(route('article.store'), $this->data(['description' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'description' => [
                        trans('validation.required', ['attribute' => 'description'])
                    ]
                ]
            ]);

        //status field is required
        $this->loggedInUser->postJson(route('article.store'), $this->data(['status' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'status' => [
                        trans('validation.required', ['attribute' => 'status'])
                    ]
                ]
            ]);
    }

    /** @test */
    public function canStoreAnArticle(): void
    {
        $this->loggedInUser->postJson(route('article.store'), $data = $this->data())
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
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
            ])
            ->assertJson([
                'status' => true,
                'message' => trans('response.success.create', ['attribute' => 'Article']),
                'data' => [
                    'type' => 'article',
                    'attributes' => [
                        'title' => $data['title'],
                        'status' => $data['status'],
                        'user' => [
                            'data' => [
                                'type' => 'user',
                                'id' => $this->user->uuid,
                                'attributes' => [
                                    'first_name' => $this->user->first_name,
                                    'last_name' => $this->user->last_name,
                                    'name' => $this->user->first_name . ' ' . $this->user->last_name,
                                    'email' => $this->user->email,
                                ],
                                'link' => [
                                    'self' => route('user.profile'),
                                ],
                            ],
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('articles', [
            "title" => $data["title"],
            "status" => $data["status"],
        ]);
    }

    /** @test */
    public function willThrowValidationErrorWhileUpdatingAnArticleWithWrongInput(): void
    {
        $article = $this->create(Article::class);

        //title field is required
        $this->loggedInUser->patchJson(route('article.update', ['uuid' => $article->uuid]), $this->data(['title' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => [
                        trans('validation.required', ['attribute' => 'title'])
                    ]
                ]
            ]);

        //title can have max 150 character
        $this->loggedInUser->patchJson(route('article.update', ['uuid' => $article->uuid]), $this->data(['title' => Str::random(200)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => [
                        trans('validation.max.string', ['attribute' => 'title', 'max' => 150])
                    ]
                ]
            ]);

        //description field is required
        $this->loggedInUser->patchJson(route('article.update', ['uuid' => $article->uuid]), $this->data(['description' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'description' => [
                        trans('validation.required', ['attribute' => 'description'])
                    ]
                ]
            ]);
        //status field is required
        $this->loggedInUser->patchJson(route('article.update', ['uuid' => $article->uuid]), $this->data(['status' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'status' => [
                        trans('validation.required', ['attribute' => 'status'])
                    ]
                ]
            ]);
    }

    /** @test */
    public function willThrow404IfAnArticleToUpdateIsNotFound(): void
    {
        $this->loggedInUser->patchJson(route('article.update', ['uuid' => 'dertgfdklidop']), $this->data())
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function canUpdateAnArticle(): void
    {
        $article = $this->create(Article::class);

        $this->loggedInUser->patchJson(
            route('article.update', ['uuid' => $article->uuid]),
            $data = $this->data(['title' => 'This is a new title'])
        )
            ->assertJson([
                'status' => true,
                'message' => trans('response.success.update', ['attribute' => 'Article']),
                'data' => [
                    'type' => 'article',
                    'id' => $article->uuid,
                    'attributes' => [
                        'title' => $data['title'],
                        'status' => $data['status'],
                        'user' => [
                            'data' => [
                                'type' => 'user',
                                'id' => $this->user->uuid,
                                'attributes' => [
                                    'first_name' => $this->user->first_name,
                                    'last_name' => $this->user->last_name,
                                    'name' => $this->user->first_name . ' ' . $this->user->last_name,
                                    'email' => $this->user->email,
                                ],
                                'link' => [
                                    'self' => route('user.profile'),
                                ],
                            ],
                        ],
                    ]
                ]
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
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
            ])
            ->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'uuid' => $article->uuid,
            'title' => $data['title'],
            'status' => $data['status']
        ]);
    }

    /** @test */
    public function willThrow404ErrorIfAnArticleWeAreTryingToDeleteDoesNotExists(): void
    {
        $this->loggedInUser->deleteJson(route('article.destroy', ['uuid' => 'wrong-uuid']))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function canDeleteAnArticle(): void
    {
        $article = $this->create(Article::class);

        $this->loggedInUser->deleteJson(route('article.destroy', ['uuid' => $article->uuid]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message'
            ])->assertJson([
                'status' => true,
                'message' => trans('response.success.delete', ['attribute' => 'Article'])
            ]);
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    private function data($data = []): array
    {
        return [
            'title' => $data['title'] ?? $this->faker->unique()->sentence,
            'description' => $data['description'] ?? $this->faker->realText(1000),
            'status' => $data['status'] ?? 1,
        ];
    }
}
