<?php

namespace Tests\Feature\Posts;

use App\Http\Controllers\Posts\UpdateController;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see UpdateController
 * @group Posts
 */
class UpdateControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $post = Post::factory()->create();

        $response = $this->putJson(route('api.posts.update', $post), $post->toArray());

        $response->assertUnauthorized();
    }

    public function test_404(): void
    {
        $this->auth();

        $post = Post::factory()->make();

        $response = $this->putJson(route('api.posts.update', Str::uuid()), $post->toArray());

        $response->assertNotFound();
    }

    public function test_forbidden(): void
    {
        $this->auth();

        $post = Post::factory()->create();

        $response = $this->putJson(route('api.posts.update', $post), $post->toArray());

        $response->assertForbidden();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $update = Post::factory()->make();

        $response = $this->putJson(route('api.posts.update', $post), $update->toArray());

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Post was updated',
            ]);

        $this->assertDatabaseHas('posts', [
            'uuid' => $post->uuid,
            'title' => $update['title'],
            'content' => $update['content'],
        ]);
    }

    public function test_title_max(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'title' => Str::repeat('1234567890', 8) . '1',
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.posts.update', $post), $post->toArray());

        $response
            ->assertUnprocessable()
            ->assertInvalid('title');
    }

    public function test_title_null(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $update = Post::factory()->make([
            'title' => null,
        ]);

        $response = $this->putJson(route('api.posts.update', $post), $update->toArray());

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Post was updated',
            ]);

        $this->assertDatabaseHas('posts', [
            'uuid' => $post->uuid,
            'title' => $update['title'],
            'content' => $update['content'],
        ]);
    }

    public function test_content_max(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'content' => Str::repeat('1234567890', 900) . '1',
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.posts.update', $post), $post->toArray());

        $response
            ->assertUnprocessable()
            ->assertInvalid('content');
    }

    public function test_content_required(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'content' => null,
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(route('api.posts.update', $post), $post->toArray());

        $response
            ->assertUnprocessable()
            ->assertInvalid('content');
    }
}
