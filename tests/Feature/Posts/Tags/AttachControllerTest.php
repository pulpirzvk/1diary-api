<?php

namespace Tests\Feature\Posts\Tags;

use App\Http\Controllers\Posts\Tags\AttachController;
use App\Models\Post;
use App\Models\Tags\Tag;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see AttachController
 * @group Posts
 */
class AttachControllerTest extends TestCase
{
    public function test_unauthorized(): void
    {
        $response = $this->postJson(route('api.posts.tags.attach', [
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

        $response = $this->postJson(route('api.posts.tags.attach', [
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

        $response = $this->postJson(route('api.posts.tags.attach', [
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

        $response = $this->postJson(route('api.posts.tags.attach', [
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

        $response = $this->postJson(route('api.posts.tags.attach', [
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

        $response = $this->postJson(route('api.posts.tags.attach', [
            $post,
            $tag,
        ]));

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Tag was attached',
            ]);

        $this->assertDatabaseHas('post_tag', [
            'post_uuid' => $post->getKey(),
            'tag_uuid' => $tag->getKey(),
        ]);
    }

    public function test_success_with_yet_attached_tag(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $tag = Tag::factory()->create([
            'user_id' => $user->id,
        ]);

        $post->tags()->attach($tag);

        $response = $this->postJson(route('api.posts.tags.attach', [
            $post,
            $tag,
        ]));

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Tag was attached',
            ]);

        $this->assertDatabaseHas('post_tag', [
            'post_uuid' => $post->getKey(),
            'tag_uuid' => $tag->getKey(),
        ]);
    }
}
