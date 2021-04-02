<?php

namespace Tests\Feature\Api\Auth;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class NewPasswordTest extends TestCase
{
    use RefreshDatabase;

    use RefreshDatabase, WithFaker;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create(User::class);
    }

    /** @test */
    public function canResetPasswordWithValidTokenAndValidInput(): void
    {
        $this->postJson(route('password.reset'), $this->data())
            ->assertJson([
                'status' => true,
                'message' => trans('passwords.reset')
            ])
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function cannotResetPasswordWithInvalidTokenAndWrongInput(): void
    {
        //token field is required
        $this->postJson(route('password.reset'), $this->data(['token' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'token' => [
                        trans('validation.required', ['attribute' => 'token'])
                    ]
                ]
            ]);

        //Must be a valid token
        $this->postJson(route('password.reset'), $this->data(['token' => 'invalid']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('passwords.token')
                    ]
                ]
            ]);

        //email field is required
        $this->postJson(route('password.reset'), $this->data(['email' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.required', ['attribute'=>'email'])
                    ]
                ]
            ]);

        //email should be valid
        $this->postJson(route('password.reset'), $this->data(['email' => '123fcd']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.email', ['attribute'=>'email'])
                    ]
                ]
            ]);

        //password field is required
        $this->postJson(route('password.reset'), $this->data(['password' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        trans('validation.required', ['attribute' => 'password'])
                    ]
                ]
            ]);

        //The password must be at least 8 characters.
        $this->postJson(route('password.reset'), $this->data(['password' => '123', 'password_confirmation' => '123']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        trans('validation.min.string', ['attribute' => 'password', 'min' => 8])
                    ]
                ]
            ]);

        //The password confirmation does not match.
        $this->postJson(route('password.reset'), $this->data(['password' => '123456789']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        trans('validation.confirmed', ['attribute' => 'password'])
                    ]
                ]
            ]);

    }

    private function data(array $data = []): array
    {
        return [
            'token' => $data['token'] ?? $this->token($this->user),
            'email' => $data['email'] ?? $this->user->email,
            'password' => $data['password'] ?? 'new-password',
            'password_confirmation' => $data['password_confirmation'] ?? 'new-password'
        ];
    }

    private function token($user)
    {
        return Password::broker()->createToken($user);
    }
}
