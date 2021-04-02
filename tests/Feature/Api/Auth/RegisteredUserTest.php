<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisteredUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function canRegisterAUserIfEmailVerificationIsNotRequired(): void
    {

        if (new User instanceof MustVerifyEmail) {
            $this->expectNotToPerformAssertions();
            return;
        }

        $this->postJson(route('register'), $data = $this->data())
            ->assertJson([
                'status' => true,
                'message' => trans('auth.login'),
                'data' => [
                    'data' => [
                        'type' => 'user',

                        'attributes' => [
                            'first_name' => $data['first_name'],
                            'last_name' => $data['last_name'],
                            'name' => $data['first_name'] . ' ' . $data['last_name'],
                            'email' => $data['email'],
                        ]
                    ],
                ],
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
                            'avatar',
                        ],
                        'link' => [
                            'self',
                        ],
                    ],
                ],
            ])
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
    }

    /** @test */
    public function canRegisterAUserIfEmailVerificationIsRequired(): void
    {
        if (!new User instanceof MustVerifyEmail) {
            $this->expectNotToPerformAssertions();
            return;
        }

        $this->postJson(route('register'), $data = $this->data())
            ->assertJson([
                "status" => true,
                "verify" => true,
                "message" => trans('verification.sent')
            ])
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

    }

    /** @test */
    public function willThrowErrorsIfUserRegistersWithWrongInputData(): void
    {
        //first_name field is required
        $this->postJson(route('register'), $this->data(['first_name' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'first_name' => [
                        trans('validation.required', ['attribute' => 'first name'])
                    ]
                ]
            ]);

        //The first name may not be greater than 100 characters.
        $this->postJson(route('register'), $this->data(['first_name' => Str::random(300)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'first_name' => [
                        trans('validation.max.string', ['attribute' => 'first name', 'max' => 100])
                    ]
                ]
            ]);

        //last name field is required
        $this->postJson(route('register'), $this->data(['last_name' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'last_name' => [
                        trans('validation.required', ['attribute' => 'last name'])
                    ]
                ]
            ]);

        //The last name may not be greater than 100 characters.
        $this->postJson(route('register'), $this->data(['last_name' => Str::random(300)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'last_name' => [
                        trans('validation.max.string', ['attribute' => 'last name', 'max' => 100])
                    ]
                ]
            ]);

        //email field is required
        $this->postJson(route('register'), $this->data(['email' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.required', ['attribute' => 'email'])
                    ]
                ]
            ]);

        //email can have max 255 character
        $this->postJson(route('register'), $this->data(['email' => Str::random(260) . '@yahoo.com']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.max.string', ['attribute' => 'email', 'max' => 255])
                    ]
                ]
            ]);
        //email should be valid
        $this->postJson(route('register'), $this->data(['email' => '123fcd']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        trans('validation.email', ['attribute' => 'email'])
                    ]
                ]
            ]);

        //password field is required
        $this->postJson(route('register'), $this->data(['password' => '']))
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
        $this->postJson(route('register'), $this->data(['password' => '123', 'password_confirmation' => '123']))
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
        $this->postJson(route('register'), $this->data(['password' => '123456789']))
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
            'first_name' => $data['first_name'] ?? $this->faker->firstName,
            'last_name' => $data['last_name'] ?? $this->faker->lastName,
            'email' => $data['email'] ?? $this->faker->unique()->safeEmail,
            'password' => $data['password'] ?? 'password',
            'password_confirmation' => $data['password_confirmation'] ?? 'password'
        ];
    }
}
