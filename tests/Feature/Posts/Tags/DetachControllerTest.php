<?php

namespace Tests\Feature\Posts\Tags;

use App\Http\Controllers\Posts\Tags\DetachController;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see DetachController
 * @group Posts
 */
class DetachControllerTest extends TestCase
{
    public function test_unauthorized(): void
    {
        $response = $this->deleteJson(route('api.posts.tags.detach', [
            Str::uuid(),
            Str::uuid(),
        ]));

        $response->assertUnauthorized();
    }

    public function test_post_404(): void
    {
        $user = $this->auth();

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson(route('api.posts.tags.detach', [
            Str::uuid(),
            $tag,
        ]));

        $response->assertNotFound();
    }

    public function test_tag_404(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson(route('api.posts.tags.detach', [
            $post,
            Str::uuid(),
        ]));

        $response->assertNotFound();
    }

    public function test_post_forbidden(): void
    {
        $user = $this->auth();

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $post = Post::factory()->create();

        $response = $this->deleteJson(route('api.posts.tags.detach', [
            $post,
            $tag,
        ]));

        $response->assertForbidden();
    }

    public function test_tag_forbidden(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $tag = Tag::factory()->create();

        $response = $this->deleteJson(route('api.posts.tags.detach', [
            $post,
            $tag,
        ]));

        $response->assertForbidden();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $post->tags()->attach($tag);

        $response = $this->deleteJson(route('api.posts.tags.detach', [
            $post,
            $tag,
        ]));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Tag was detached',
            ]);

        $this->assertDatabaseMissing('post_tag', [
            'post_uuid' => $post->getKey(),
            'tag_uuid' => $tag->getKey(),
        ]);
    }

    public function test_success_with_yet_detached_tag(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson(route('api.posts.tags.detach', [
            $post,
            $tag,
        ]));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Tag was detached',
            ]);

        $this->assertDatabaseMissing('post_tag', [
            'post_uuid' => $post->getKey(),
            'tag_uuid' => $tag->getKey(),
        ]);
    }
}
