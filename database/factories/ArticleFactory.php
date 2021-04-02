<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'user_id' => function(){
                return User::all()->random();
            },
            'title' => $title = $this->faker->unique()->realText(140),
            'slug' => Str::slug($title),
            'description' => $this->faker->realText(2000),
            'status' => 1,
            'created_at' => $created = $this->faker->dateTimeBetween('-2 years', '-2 months', 'Europe/London'),
            'updated_at' => $this->faker->dateTimeBetween($created, strtotime('+5 days'), 'Europe/London')
        ];
    }
}
