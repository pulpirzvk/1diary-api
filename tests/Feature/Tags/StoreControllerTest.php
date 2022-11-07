<?php

namespace Tests\Feature\Tags;

use App\Http\Controllers\Tags\StoreController;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see StoreController
 * @group Tags
 */
class StoreControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->postJson(route('api.tags.store'), []);

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $tag = Tag::factory()->make([
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(route('api.tags.store'), $tag->toArray());

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ],
            ])
            ->assertJson([
                'data' => [
                    'name' => $tag->name,
                ],
            ]);
    }

    public function test_name_required(): void
    {
        $user = $this->auth();

        $tag = Tag::factory()->make([
            'user_id' => $user->id,
            'name' => null,
        ]);

        $response = $this->postJson(route('api.tags.store'), $tag->toArray());

        $response
            ->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_max(): void
    {
        $user = $this->auth();

        $tag = Tag::factory()->make([
            'user_id' => $user->id,
            'name' => Str::repeat('1', 21),
        ]);

        $response = $this->postJson(route('api.tags.store'), $tag->toArray());

        $response
            ->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_unique(): void
    {
        $user = $this->auth();

        Tag::factory()->create([
            'user_id' => $user->id,
            'name' => 'test',
        ]);

        $tag = Tag::factory()->make([
            'user_id' => $user->id,
            'name' => 'test',
        ]);

        $response = $this->postJson(route('api.tags.store'), $tag->toArray());

        $response
            ->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_unique_case_sensitivity(): void
    {
        $user = $this->auth();

        $name = Str::random(10);

        Tag::factory()->create([
            'user_id' => $user->id,
            'name' => Str::lower($name),
        ]);

        $tag = Tag::factory()->make([
            'user_id' => $user->id,
            'name' => Str::upper($name),
        ]);

        $response = $this->postJson(route('api.tags.store'), $tag->toArray());

        $response
            ->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_unique_for_current_user(): void
    {
        $user = $this->auth();

        $name = Str::random(10);

        Tag::factory()->create([
            'name' => $name,
        ]);

        $tag = Tag::factory()->make([
            'user_id' => $user->id,
            'name' => $name,
        ]);

        $response = $this->postJson(route('api.tags.store'), $tag->toArray());

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ],
            ])
            ->assertJson([
                'data' => [
                    'name' => $tag->name,
                ],
            ]);
    }

    public function test_name_non_latin(): void
    {
        $user = $this->auth();

        $name = Str::random(10) . 'аяä_x';

        Tag::factory()->create([
            'name' => $name,
        ]);

        $tag = Tag::factory()->make([
            'user_id' => $user->id,
            'name' => $name,
        ]);

        $response = $this->postJson(route('api.tags.store'), $tag->toArray());

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ],
            ])
            ->assertJson([
                'data' => [
                    'name' => $tag->name,
                ],
            ]);
    }
}
