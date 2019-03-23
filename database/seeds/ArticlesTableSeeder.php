<?php

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::truncate();

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++)
        {
            Article::create([
                'title' => $faker->sentence,
                'slug' => $faker->slug,
                'user_id' => $faker->numberBetween($min = 1, $max = 10),
                'tag_id' => $faker->numberBetween($min = 1, $max = 10),
                'status' => '1',
                'description' => $faker->paragraph,
                'excerpts' => $faker->sentence,
                'published_at' => $faker->DateTime('Y-m-d H:i:s', 'UTC'),
            ]);
        }
    }
}
