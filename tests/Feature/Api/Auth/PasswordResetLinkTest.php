<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canSendPasswordResetLinkEmail(): void
    {
        $user = $this->create(User::class);

        Notification::fake();

        $this->postJson(route('password.email'), ['email' => $user->email])
            ->assertJson([
                'status' => true,
                'message' => trans('passwords.sent')
            ])
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertStatus(Response::HTTP_OK);

        Notification::assertSentTo([$user], ResetPassword::class);

        $this->assertNotNull($token = DB::table('password_resets')->first());

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    /** @test */
    public function canThrowErrorsWithWrongInput(): void
    {
        //email field is required
        $this->postJson(route('password.email'), ['email' => ''])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.required', ['attribute'=>'email'])
                    ]
                ]
            ]);

        //email address should be valid
        $this->postJson(route('password.email'), ['email' => '123fcd'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.email', ['attribute'=>'email'])
                    ]
                ]
            ]);

        //email address should exists in the system
        $this->postJson(route('password.email'), ['email' => 'nouser@example.com'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('passwords.user')
                    ]
                ]
            ]);
    }
}
