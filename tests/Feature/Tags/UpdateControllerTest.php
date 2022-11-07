<?php

namespace Tests\Feature\Tags;

use App\Http\Controllers\Tags\DeleteController;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see DeleteController
 * @group Tags
 */
class UpdateControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->putJson(route('api.tags.update', $tag), []);

        $response->assertUnauthorized();
    }

    public function test_404(): void
    {
        $tag = Tag::factory()->create();

        $this->auth($tag->user);

        $response = $this->putJson(route('api.tags.update', Str::uuid()), []);

        $response->assertNotFound();
    }

    public function test_forbidden(): void
    {
        $tag = Tag::factory()->create();

        $this->auth();

        $response = $this->putJson(route('api.tags.update', $tag), []);

        $response->assertForbidden();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $name = Str::random(4);

        $response = $this->putJson(route('api.tags.update', $tag), [
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
                'message' => 'Tag was updated',
            ]);

        $this->assertDatabaseHas('tags', [
            'uuid' => $tag->uuid,
            'name' => $name,
            'slug' => Tag::makeSlug($name),
        ]);
    }

    public function test_name_required(): void
    {
        $user = $this->auth();

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.tags.update', $tag), [
            'name' => null,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_max(): void
    {
        $user = $this->auth();

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.tags.update', $tag), [
            'name' => Str::repeat('a', 21),
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_unique(): void
    {
        $user = $this->auth();

        $test = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.tags.update', $tag), [
            'name' => $test->name,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_unique_case_sensitivity(): void
    {
        $user = $this->auth();

        $test = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.tags.update', $tag), [
            'name' => Str::upper($test->name),
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_unique_for_current_user(): void
    {
        $user = $this->auth();

        $name = Str::random(10);

        Tag::factory()->create([
            'name' => $name,
        ]);

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.tags.update', $tag), [
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
                'message' => 'Tag was updated',
            ]);

        $this->assertDatabaseHas('tags', [
            'uuid' => $tag->uuid,
            'name' => $name,
            'slug' => Tag::makeSlug($name),
        ]);
    }

    public function test_name_non_latin(): void
    {
        $user = $this->auth();

        $name = Str::random(10) . 'аяä_x';

        Tag::factory()->create([
            'user_id' => $user->id,
            'name' => $name,
        ]);

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.tags.update', $tag), [
            'name' => $name,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }
}
