<?php

namespace Tests\Feature\Posts;

use App\Http\Controllers\Posts\ShowController;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see ShowController
 * @group Posts
 */
class ShowControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_404(): void
    {
        $post = Post::factory()->create();

        $this->auth($post->user);

        $response = $this->getJson(route('api.posts.show', Str::uuid()));

        $response->assertNotFound();
    }

    public function test_forbidden(): void
    {
        $post = Post::factory()->create();

        $this->auth();

        $response = $this->getJson(route('api.posts.show', $post));

        $response->assertForbidden();
    }

    public function test_unauthorized(): void
    {
        $post = Post::factory()->create();

        $response = $this->getJson(route('api.posts.show', $post));

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $post = Post::factory()->create();

        $this->auth($post->user);

        $response = $this->getJson(route('api.posts.show', $post));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'content',
                    'created_at',
                    'published_at',
                ],
            ])
            ->assertExactJson([
                'data' => [
                    'id' => $post->uuid,
                    'title' => $post->title,
                    'content' => $post->content,
                    'created_at' => $post->created_at->toAtomString(),
                    'published_at' => $post->published_at->toAtomString(),
                ],
            ]);
    }
}
