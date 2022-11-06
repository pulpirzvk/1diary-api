<?php

namespace Tests\Feature\Posts;

use App\Http\Controllers\Posts\StoreController;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see StoreController
 * @group Posts
 */
class StoreControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $post = Post::factory()->make();

        $response = $this->postJson(route('api.posts.store'), $post->toArray());

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $post = Post::factory()->make([
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(route('api.posts.store'), $post->toArray());

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'content',
                    'created_at',
                    'published_at',
                ],
            ])
            ->assertJson([
                'data' => [
                    'title' => $post['title'],
                    'content' => $post['content'],
                ],
            ]);
    }

    public function test_title_max(): void
    {
        $user = $this->auth();

        $post = Post::factory()->make([
            'title' => Str::repeat('1234567890', 8) . '1',
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(route('api.posts.store'), $post->toArray());

        $response
            ->assertUnprocessable()
            ->assertInvalid('title');
    }

    public function test_title_null(): void
    {
        $user = $this->auth();

        $post = Post::factory()->make([
            'title' => null,
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(route('api.posts.store'), $post->toArray());

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'content',
                    'created_at',
                    'published_at',
                ],
            ])
            ->assertJson([
                'data' => [
                    'title' => $post['title'],
                    'content' => $post['content'],
                ],
            ]);
    }

    public function test_content_max(): void
    {
        $user = $this->auth();

        $post = Post::factory()->make([
            'content' => Str::repeat('1234567890', 900) . '1',
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(route('api.posts.store'), $post->toArray());

        $response
            ->assertUnprocessable()
            ->assertInvalid('content');
    }

    public function test_content_required(): void
    {
        $user = $this->auth();

        $post = Post::factory()->make([
            'content' => null,
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(route('api.posts.store'), $post->toArray());

        $response
            ->assertUnprocessable()
            ->assertInvalid('content');
    }

    public function test_published_at_format(): void
    {
        $user = $this->auth();

        $post = Post::factory()->make([
            'user_id' => $user->id,
        ]);

        $data = array_merge($post->toArray(), ['published_at' => '2022-01-01']);

        $response = $this->postJson(route('api.posts.store'), $data);

        $response
            ->assertUnprocessable()
            ->assertInvalid('published_at');
    }

    public function test_published_at_null(): void
    {
        $user = $this->auth();

        $post = Post::factory()->make([
            'published_at' => null,
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(route('api.posts.store'), $post->toArray());

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'content',
                    'created_at',
                    'published_at',
                ],
            ])
            ->assertJson([
                'data' => [
                    'title' => $post['title'],
                    'content' => $post['content'],
                ],
            ]);
    }
}
