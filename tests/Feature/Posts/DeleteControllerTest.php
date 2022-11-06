<?php

namespace Tests\Feature\Posts;

use App\Http\Controllers\Posts\DeleteController;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see DeleteController
 * @group Posts
 */
class DeleteControllerTest extends TestCase
{

    use DatabaseTransactions;

    public function test_success(): void
    {
        $post = Post::factory()->create();

        $this->auth($post->user);

        $response = $this->deleteJson(route('api.posts.delete', $post));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Post was deleted',
            ]);

        $this->assertSoftDeleted($post);
    }

    public function test_404(): void
    {
        $post = Post::factory()->create();

        $this->auth($post->user);

        $response = $this->deleteJson(route('api.posts.delete', Str::uuid()));

        $response->assertNotFound();

        $this->assertNotSoftDeleted($post);
    }

    public function test_unauthorized(): void
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson(route('api.posts.delete', $post));

        $response->assertUnauthorized();
    }

    public function test_forbidden(): void
    {
        $post = Post::factory()->create();

        $this->auth();

        $response = $this->deleteJson(route('api.posts.show', $post));

        $response->assertForbidden();

        $this->assertNotSoftDeleted($post);
    }
}
