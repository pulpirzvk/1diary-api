<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all()->load('tags')->keyBy('id');

        Post::all()->map(static function (Post $post) use ($users) {
            $tags = $users->get($post->user_id)->tags->random(3);
            $post->tags()->attach($tags);
        });
    }
}
