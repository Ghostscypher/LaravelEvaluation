<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Website;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Website::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Website::factory(5)->has(Post::factory(5))->create();
    }
}
