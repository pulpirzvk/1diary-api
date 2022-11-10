<?php

namespace Database\Seeders;

use App\Models\Tags\Group;
use Illuminate\Database\Seeder;

class TagGroupSeeder extends Seeder
{
    public function run(): void
    {
        Group::all()->load('user', 'user.tags')->each(static function (Group $group) {
            $group->tags()->attach($group->user->tags->random(4));
        });
    }
}
