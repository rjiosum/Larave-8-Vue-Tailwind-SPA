<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class EmailVerificationNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canResendEmailVerificationNotification(): void
    {
        $user = $this->create(User::class, ['email_verified_at' => null]);

        Notification::fake();

        $this->postJson(route('verification.send'), ['email' => $user->email])
            ->assertJson([
                'status' => true,
                'message' => trans('verification.sent')
            ])
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertStatus(Response::HTTP_OK);

        Notification::assertSentTo(
            [$user], VerifyEmail::class
        );
    }

    /** @test */
    public function cannotResendEmailVerificationNotificationWithWrongInput(): void
    {
        //email field is required
        $this->postJson(route('verification.send'), ['email' => ''])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.required', ['attribute'=>'email'])
                    ]
                ]
            ]);

        //email can have max 255 character
        $this->postJson(route('verification.send'), ['email' => Str::random(260) . '@gmail.com'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.max.string', ['attribute'=>'email', 'max'=>255])
                    ]
                ]
            ]);

        //email should be valid
        $this->postJson(route('verification.send'), ['email' => '123fcd'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.email', ['attribute'=>'email'])
                    ]
                ]
            ]);
    }

    /** @test */
    public function willThrowUserNotFoundErrorIfEmailAddressIsNotFound(): void
    {
        $this->postJson(route('verification.send'), ['email' => 'nouser@example.com'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('verification.user')
                    ]
                ]
            ]);
    }

    /** @test */
    public function willNotResendEmailVerificationNotificationIfUserHasAlreadyVerifiedEmail(): void
    {
        $user = $this->create(User::class);
        $this->postJson(route('verification.send'), ['email' => $user->email])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('verification.already_verified')
                    ]
                ]
            ]);
    }
}
