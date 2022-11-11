<?php

namespace Tests\Feature\Posts;

use App\Http\Controllers\Posts\PublishedAtController;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see PublishedAtController
 * @group Posts
 */
class PublishedAtControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->patchJson(route('api.posts.move', Str::uuid()), []);

        $response->assertUnauthorized();
    }

    public function test_404(): void
    {
        $this->auth();

        $response = $this->patchJson(route('api.posts.move', Str::uuid()), []);

        $response->assertNotFound();
    }

    public function test_forbidden(): void
    {
        $this->auth();

        $post = Post::factory()->create();

        $response = $this->patchJson(route('api.posts.move', $post), []);

        $response->assertForbidden();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $moveTo = now()->subWeek()->format('Y-m-d H:i');

        $response = $this->patchJson(route('api.posts.move', $post), [
            'to' => $moveTo,
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Post was moved',
            ]);

        $this->assertDatabaseHas('posts', [
            'uuid' => $post->uuid,
            'published_at' => $moveTo . ':00',
        ]);
    }

    public function test_to_required(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->patchJson(route('api.posts.move', $post), [
            'to' => null,
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('to');
    }

    public function test_to_date_format(): void
    {
        $user = $this->auth();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->patchJson(route('api.posts.move', $post), [
            'to' => now()->format('Y-m-d His'),
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('to');
    }
}
