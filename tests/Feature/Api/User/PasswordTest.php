<?php

namespace Tests\Feature\Api\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $loggedInUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create(User::class);
        $this->loggedInUser = $this->actingAs($this->user, 'api');
    }

    /** @test */
    public function canUpdatePassword(): void
    {
        $this->loggedInUser->patchJson(route('password.update'), [
            'password' => 'newPassword',
            'password_confirmation' => 'newPassword'
        ])
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertJson([
                'status' => true,
                'message' => trans('response.success.update', ['attribute' => 'Password'])
            ]);
    }

    /** @test */
    public function cannotUpdatePasswordWithWrongInput(): void
    {
        //Password field is required
        $this->loggedInUser->patchJson(route('password.update'), [
            'password' => '',
            'password_confirmation' => ''
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        trans('validation.required', ['attribute' => 'password'])
                    ]
                ]
            ]);

        //Password should be minimum 8 characters
        $this->loggedInUser->patchJson(route('password.update'), [
            'password' => 'min',
            'password_confirmation' => 'min'
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        trans('validation.min.string', ['attribute' => 'password', 'min' => 8])
                    ]
                ]
            ]);

        //Password confirmation is required and must match the password
        $this->loggedInUser->patchJson(route('password.update'), [
            'password' => 'new-password',
            'password_confirmation' => ''
        ])
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

}
