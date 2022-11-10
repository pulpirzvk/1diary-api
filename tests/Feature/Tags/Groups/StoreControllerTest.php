<?php

namespace Tests\Feature\Tags\Groups;

use App\Http\Controllers\Tags\Groups\StoreController;
use App\Models\Tags\Group;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see StoreController
 * @group Tags
 * @group TagGroups
 */
class StoreControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->postJson(route('api.tag_groups.store'), []);

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $name = Str::random(20);

        $response = $this->postJson(route('api.tag_groups.store'), [
            'name' => $name,
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ],
            ])
            ->assertJsonFragment([
                'name' => $name,
            ]);

        $this->assertDatabaseHas('tag_groups', [
            'user_id' => $user->id,
            'name' => $name,
        ]);
    }

    public function test_name_max(): void
    {
        $user = $this->auth();

        Group::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(route('api.tag_groups.store'), [
            'name' => Str::repeat('a', 41),
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_required(): void
    {
        $user = $this->auth();

        Group::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(route('api.tag_groups.store'), [
            'name' => null,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }
}
