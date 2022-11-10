<?php

namespace Tests\Feature\Tags\Groups;

use App\Http\Controllers\Tags\Groups\DeleteController;
use App\Models\Tags\Group;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see DeleteController
 * @group Tags
 * @group TagGroups
 */
class DeleteControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->deleteJson(route('api.tag_groups.delete', Str::uuid()));

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $group = Group::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson(route('api.tag_groups.delete', $group));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Tag group was deleted',
            ]);

        $this->assertSoftDeleted($group);
    }

    public function test_404(): void
    {
        $this->auth();

        $response = $this->deleteJson(route('api.tag_groups.delete', Str::uuid()));

        $response->assertNotFound();
    }

    public function test_forbidden(): void
    {
        $this->auth();

        $group = Group::factory()->create();

        $response = $this->deleteJson(route('api.tag_groups.delete', $group));

        $response->assertForbidden();
    }
}
