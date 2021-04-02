<?php

namespace Tests\Feature\Api\Address;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
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
    public function canListCollectionOfPaginatedAddressResults(): void
    {
        $this->create(Country::class, [], 3);
        $this->create(Address::class, ['user_id' => $this->user->id], 30);

        $this->loggedInUser->getJson(route('address'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'address_1',
                            'address_2',
                            'town',
                            'county',
                            'postcode',
                            'country_id',
                            'country',
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
    public function willThrow404ErrorIfAddressDoesNotExists(): void
    {
        $this->loggedInUser->getJson(route('address.show', ['uuid' => 'some-random-uuid']))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function canShowAnAddress(): void
    {
        $this->create(Country::class, [], 3);
        $address = $this->create(Address::class, ['user_id' => $this->user->id]);

        $this->loggedInUser->getJson(route('address.show', ['uuid' => $address->uuid]))
            ->assertJson([
                'status' => true,
                'message' => '',
                'data' => [
                    'type' => 'address',
                    'id' => $address->uuid,
                    'attributes' => [
                        'address_1' => $address->address_1,
                        'address_2' => $address->address_2,
                        'town' => $address->town,
                        'county' => $address->county,
                        'postcode' => $address->postcode,
                        'country_id' => $address->country_id,
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
                        'created_at' => $address->created_at->format('d-m-Y H:i:s'),
                        'updated_at' => $address->updated_at->format('d-m-Y H:i:s'),
                        'created_h' => $address->created_at->diffForHumans(),
                        'updated_h' => $address->updated_at->diffForHumans(),
                    ],
                    'link' => [
                        'self' => route('address.show', ['uuid' => $address->uuid]),
                    ],
                ],
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function willThrowValidationErrorWhileCreatingAnAddressWithWrongInput(): void
    {
        $this->create(Country::class, [], 3);

        //address_1 field is required
        $this->loggedInUser->postJson(route('address.store'), $this->data(['address_1' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'address_1' => [
                        trans('validation.required', ['attribute' => 'address 1'])
                    ]
                ]
            ]);

        //address_1 can have max 300 character
        $this->loggedInUser->postJson(route('address.store'), $this->data(['address_1' => Str::random(310)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'address_1' => [
                        trans('validation.max.string', ['attribute' => 'address 1', 'max' => 300])
                    ]
                ]
            ]);

        //address_2 can have max 300 character
        $this->loggedInUser->postJson(route('address.store'), $this->data(['address_2' => Str::random(310)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'address_2' => [
                        trans('validation.max.string', ['attribute' => 'address 2', 'max' => 300])
                    ]
                ]
            ]);

        //town field is required
        $this->loggedInUser->postJson(route('address.store'), $this->data(['town' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'town' => [
                        trans('validation.required', ['attribute' => 'town'])
                    ]
                ]
            ]);

        //town can have max 100 character
        $this->loggedInUser->postJson(route('address.store'), $this->data(['town' => Str::random(110)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'town' => [
                        trans('validation.max.string', ['attribute' => 'town', 'max' => 100])
                    ]
                ]
            ]);

        //county field is required
        $this->loggedInUser->postJson(route('address.store'), $this->data(['county' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'county' => [
                        trans('validation.required', ['attribute' => 'county'])
                    ]
                ]
            ]);

        //county can have max 100 character
        $this->loggedInUser->postJson(route('address.store'), $this->data(['county' => Str::random(110)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'county' => [
                        trans('validation.max.string', ['attribute' => 'county', 'max' => 100])
                    ]
                ]
            ]);

        //postcode field is required
        $this->loggedInUser->postJson(route('address.store'), $this->data(['postcode' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'postcode' => [
                        trans('validation.required', ['attribute' => 'postcode'])
                    ]
                ]
            ]);

        //postcode can have max 20 character
        $this->loggedInUser->postJson(route('address.store'), $this->data(['postcode' => Str::random(110)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'postcode' => [
                        trans('validation.max.string', ['attribute' => 'postcode', 'max' => 20])
                    ]
                ]
            ]);

        //country_id field is required
        $this->loggedInUser->postJson(route('address.store'), $this->data(['country_id' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'country_id' => [
                        trans('validation.required', ['attribute' => 'country id'])
                    ]
                ]
            ]);
    }

    /** @test */
    public function canStoreAnAddress(): void
    {
        $this->create(Country::class, [], 3);

        $this->loggedInUser->postJson(route('address.store'), $data = $this->data())
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'address_1',
                        'address_2',
                        'town',
                        'county',
                        'postcode',
                        'country_id',
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
                'message' => trans('response.success.create', ['attribute' => 'Address']),
                'data' => [
                    'type' => 'address',
                    'attributes' => [
                        'address_1' => $data['address_1'],
                        'address_2' => $data['address_2'],
                        'town' => $data['town'],
                        'county' => $data['county'],
                        'postcode' => $data['postcode'],
                        'country_id' => $data['country_id'],
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

        $this->assertDatabaseHas('addresses', [
            "address_1" => $data["address_1"],
            "address_2" => $data["address_2"],
            'town' => $data['town'],
            'county' => $data['county'],
            'postcode' => $data['postcode'],
            'country_id' => $data['country_id'],
        ]);
    }

    /** @test */
    public function willThrowValidationErrorWhileUpdatingAnAddressWithWrongInput(): void
    {
        $this->create(Country::class, [], 3);
        $address = $this->create(Address::class, ['user_id' => $this->user->id]);

        //address_1 field is required
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => $address->uuid]), $this->data(['address_1' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'address_1' => [
                        trans('validation.required', ['attribute' => 'address 1'])
                    ]
                ]
            ]);

        //address_1 can have max 300 character
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => $address->uuid]), $this->data(['address_1' => Str::random(310)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'address_1' => [
                        trans('validation.max.string', ['attribute' => 'address 1', 'max' => 300])
                    ]
                ]
            ]);

        //address_2 can have max 300 character
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => $address->uuid]), $this->data(['address_2' => Str::random(310)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'address_2' => [
                        trans('validation.max.string', ['attribute' => 'address 2', 'max' => 300])
                    ]
                ]
            ]);

        //town field is required
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => $address->uuid]), $this->data(['town' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'town' => [
                        trans('validation.required', ['attribute' => 'town'])
                    ]
                ]
            ]);

        //town can have max 100 character
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => $address->uuid]), $this->data(['town' => Str::random(110)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'town' => [
                        trans('validation.max.string', ['attribute' => 'town', 'max' => 100])
                    ]
                ]
            ]);

        //county field is required
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => $address->uuid]), $this->data(['county' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'county' => [
                        trans('validation.required', ['attribute' => 'county'])
                    ]
                ]
            ]);

        //county can have max 100 character
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => $address->uuid]), $this->data(['county' => Str::random(110)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'county' => [
                        trans('validation.max.string', ['attribute' => 'county', 'max' => 100])
                    ]
                ]
            ]);

        //postcode field is required
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => $address->uuid]), $this->data(['postcode' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'postcode' => [
                        trans('validation.required', ['attribute' => 'postcode'])
                    ]
                ]
            ]);

        //postcode can have max 20 character
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => $address->uuid]), $this->data(['postcode' => Str::random(110)]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'postcode' => [
                        trans('validation.max.string', ['attribute' => 'postcode', 'max' => 20])
                    ]
                ]
            ]);

        //country_id field is required
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => $address->uuid]), $this->data(['country_id' => '']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'country_id' => [
                        trans('validation.required', ['attribute' => 'country id'])
                    ]
                ]
            ]);

    }

    /** @test */
    public function willThrow404IfAnAddressToUpdateIsNotFound(): void
    {
        $this->create(Country::class, [], 3);
        $this->loggedInUser->patchJson(route('address.update', ['uuid' => 'dertgfdklidop']), $this->data())
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function canUpdateAnAddress(): void
    {
        $this->create(Country::class, [], 3);
        $address = $this->create(Address::class, ['user_id' => $this->user->id]);

        $this->loggedInUser->patchJson(
            route('address.update', ['uuid' => $address->uuid]),
            $data = $this->data(['address_1' => '75 Amity Road'])
        )
            ->assertJson([
                'status' => true,
                'message' => trans('response.success.update', ['attribute' => 'Address']),
                'data' => [
                    'type' => 'address',
                    'id' => $address->uuid,
                    'attributes' => [
                        'address_1' => $data['address_1'],
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
                        'address_1',
                        'address_2',
                        'town',
                        'county',
                        'postcode',
                        'country_id',
                        'country',
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

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'uuid' => $address->uuid,
            'address_1' => $data['address_1']
        ]);
    }

    /** @test */
    public function willThrow404ErrorIfAnAddressWeAreTryingToDeleteDoesNotExists(): void
    {
        $this->loggedInUser->deleteJson(route('address.destroy', ['uuid' => 'wrong-uuid']))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function canDeleteAnAddress(): void
    {
        $this->create(Country::class, [], 3);
        $address = $this->create(Address::class, ['user_id' => $this->user->id]);

        $this->loggedInUser->deleteJson(route('address.destroy', ['uuid' => $address->uuid]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message'
            ])->assertJson([
                'status' => true,
                'message' => trans('response.success.delete', ['attribute' => 'Address'])
            ]);
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    private function data($data = []): array
    {
        $country_id = $data['country_id'] ?? Country::all()->random()->id;

        return [
            'address_1' => $data['address_1'] ?? $this->faker->streetName,
            'address_2' => $data['address_2'] ?? $this->faker->secondaryAddress,
            'town' => $data['town'] ?? $this->faker->city,
            'county' => $data['county'] ?? $this->faker->city,
            'postcode' => $data['postcode'] ?? $this->faker->postcode,
            'country_id' => $country_id,
        ];
    }
}
