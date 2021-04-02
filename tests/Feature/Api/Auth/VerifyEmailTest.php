<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canVerifyUserEmailAddress(): void
    {
        $user = $this->create(User::class, ['email_verified_at' => null]);

        Event::fake();

        $url = $this->url($user->id, $user->getEmailForVerification());

        $this->getJson($url)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => true,
                'message' => trans('verification.verified')
            ]);

        Event::assertDispatched(Verified::class, function (Verified $verified) use ($user) {
            return $verified->user->id === $user->id;
        });
    }

    /** @test */
    public function willThrowErrorIfUserIdSignatureIsInvalid(): void
    {
        $user = $this->create(User::class, ['email_verified_at' => null]);

        $url = $this->url($user->id - 2, $user->getEmailForVerification());

        $this->getJson($url)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson([
                'status' => false,
                'message' => trans('verification.user')
            ]);

    }

    /** @test */
    public function willThrowErrorIfUserEmailSignatureIsInvalid(): void
    {
        $user = $this->create(User::class, ['email_verified_at' => null]);

        $url = $this->url($user->id, 'nouser@example.com');

        $this->getJson($url)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson([
                'status' => false,
                'message' => trans('verification.invalid')
            ]);

    }

    /** @test */
    public function willNotVerifyIfUserHasAlreadyVerifiedEmail(): void
    {
        $user = $this->create(User::class);

        $url = $this->url($user->id, $user->getEmailForVerification());

        $this->getJson($url)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => true,
                'message' => trans('verification.already_verified')
            ]);
    }

    private function url(int $id, string $hash): string
    {
        $expire = Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60));
        return URL::temporarySignedRoute(
            'verification.verify',
            $expire,
            [
                'id' => $id,
                'hash' => sha1($hash),
            ]
        );
    }

}
