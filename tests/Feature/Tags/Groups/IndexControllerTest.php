<?php

namespace Tests\Feature\Tags\Groups;

use App\Http\Controllers\Tags\Groups\IndexController;
use App\Models\Tags\Group;
use Tests\TestCase;

/**
 * @see IndexController
 * @group Tags
 * @group TagGroups
 */
class IndexControllerTest extends TestCase
{
    public function test_unauthorized(): void
    {
        $response = $this->getJson(route('api.tag_groups.index'));

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        Group::factory(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson(route('api.tag_groups.index'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ],
                ],
            ])
            ->assertJsonCount(3, 'data');
    }
}
