<?php

namespace Tests\Feature\Tags;

use App\Http\Controllers\Tags\IndexController;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @see IndexController
 * @group Tags
 */
class IndexControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->getJson(route('api.tags.index'));

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = User::factory()->has(Tag::factory(3))->create();

        $this->auth($user);

        $response = $this->getJson(route('api.tags.index'));

        $response
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ],
                ],
            ]);
    }
}
