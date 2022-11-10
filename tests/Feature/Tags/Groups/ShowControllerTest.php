<?php

namespace Tests\Feature\Tags\Groups;

use App\Http\Controllers\Tags\Groups\ShowController;
use App\Models\Tags\Group;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see ShowController
 * @group Tags
 * @group TagGroups
 */
class ShowControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->getJson(route('api.tag_groups.show', Str::uuid()));

        $response->assertUnauthorized();
    }

    public function test_404(): void
    {
        $this->auth();

        $response = $this->getJson(route('api.tag_groups.show', Str::uuid()));

        $response->assertNotFound();
    }

    public function test_forbidden(): void
    {
        $this->auth();

        $group = Group::factory()->create();

        $response = $this->getJson(route('api.tag_groups.show', $group));

        $response->assertForbidden();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $group = Group::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson(route('api.tag_groups.show', $group));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ],
            ])
            ->assertExactJson([
                'data' => [
                    'id' => $group->getKey(),
                    'name' => $group->name,
                ],
            ]);
    }
}
