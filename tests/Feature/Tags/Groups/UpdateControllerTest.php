<?php

namespace Tests\Feature\Tags\Groups;

use App\Http\Controllers\Tags\Groups\UpdateController;
use App\Models\Tags\Group;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see UpdateController
 * @group Tags
 * @group TagGroups
 */
class UpdateControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->putJson(route('api.tag_groups.update', Str::uuid()), []);

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $group = Group::factory()->create([
            'user_id' => $user->id,
        ]);

        $name = Str::random(20);

        $response = $this->putJson(route('api.tag_groups.update', $group), [
            'name' => $name,
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Tag group was updated',
            ]);

        $this->assertDatabaseHas('tag_groups', [
            'uuid' => $group->getKey(),
            'name' => $name,
        ]);
    }

    public function test_404(): void
    {
        $this->auth();

        $response = $this->putJson(route('api.tag_groups.update', Str::uuid()), []);

        $response->assertNotFound();
    }

    public function test_forbidden(): void
    {
        $this->auth();

        $group = Group::factory()->create();

        $response = $this->putJson(route('api.tag_groups.update', $group), []);

        $response->assertForbidden();
    }

    public function test_name_max(): void
    {
        $user = $this->auth();

        $group = Group::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.tag_groups.update', $group), [
            'name' => Str::repeat('a', 41),
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_required(): void
    {
        $user = $this->auth();

        $group = Group::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.tag_groups.update', $group), [
            'name' => null,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }
}
