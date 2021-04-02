<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthenticatedSessionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create(User::class);
    }

    /** @test */
    public function canLoginUserUsingValidCredentials(): void
    {
        $this->postJson(route('login'), [
            'email' => $this->user->email,
            'password' => 'password'
        ])
            ->assertJson([
                'status' => true,
                'message' => trans('auth.login'),
                'data' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user->uuid,
                        'attributes' => [
                            'first_name' => $this->user->first_name,
                            'last_name' => $this->user->last_name,
                            'name' => $this->user->first_name . ' ' . $this->user->last_name,
                            'email' => $this->user->email
                        ]
                    ]
                ]
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'first_name',
                            'last_name',
                            'name',
                            'email',
                            'avatar'
                        ],
                        'link' => [
                            'self'
                        ]
                    ]
                ]
            ])
            ->assertCookie(config('passport.cookie.name'))
            ->assertCookieNotExpired(config('passport.cookie.name'))
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function cannotLoginUserUsingInvalidCredentials(): void
    {
        $this->postJson(route('login'), [
            'email' => $this->user->email,
            'password' => 'wrong-password'
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [trans('auth.failed')],
                ],
            ])
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                ]
            ]);

    }

    /** @test */
    public function canLogoutUser(): void
    {
        Passport::actingAs($this->user);

        $this->postJson(route('logout'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'status' => true,
                'message' => trans('auth.logout'),
                'data' => [],
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data',
            ])
            ->assertCookieExpired(config('passport.cookie.name'));
    }
}
