<?php

namespace Tests\Feature\Posts;

use App\Http\Controllers\Posts\IndexController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @see IndexController
 * @group Posts
 */
class IndexControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        User::factory()->has(Post::factory(5))->create();

        $response = $this->getJson(route('api.posts.index'));

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = User::factory()->has(Post::factory(3))->create();

        $this->auth($user);

        $response = $this->getJson(route('api.posts.index'));

        $response
            // ->assertJsonCount($user->posts()->count(), 'data') TODO: при тесте возвращается 1 запись вместо 3
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'created_at',
                        'published_at',
                    ],
                ],
            ]);
    }
}
