<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tags\Group;
use App\Models\Tags\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)
            ->has(Post::factory(10))
            ->has(Group::factory(3), 'tagGroups')
            ->has(Tag::factory(10))
            ->create();
    }
}
