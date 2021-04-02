<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function(){
                return User::all()->random();
            },
            'address_1' => $this->faker->streetName,
            'address_2' => $this->faker->secondaryAddress,
            'town' => $this->faker->city,
            'county' => $this->faker->city,
            'postcode' => $this->faker->postcode,
            'country_id' => function(){
                return Country::all()->random();
            },
            'created_at' => $created = $this->faker->dateTimeBetween('-2 years', '-2 months', 'Europe/London'),
            'updated_at' => $this->faker->dateTimeBetween($created, strtotime('+5 days'), 'Europe/London')
        ];
    }
}
