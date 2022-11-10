<?php

namespace Database\Factories\Tags;

use App\Models\Tags\Group;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Group>
 */
class GroupFactory extends Factory
{

    protected $model = Group::class;

    /**
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => ucfirst(fake()->words(random_int(1, 3), true)),
        ];
    }
}
