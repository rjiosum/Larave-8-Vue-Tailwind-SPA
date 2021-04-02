<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::factory(100)->create()->each(function ($article) {
            $article->slug = Str::slug($article->id . '-' . $article->title);
            return $article->save();
        });
    }
}
