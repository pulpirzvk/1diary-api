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
class DeleteControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->deleteJson(route('api.tags.delete', $tag));

        $response->assertUnauthorized();
    }

    public function test_404(): void
    {
        $tag = Tag::factory()->create();

        $this->auth($tag->user);

        $response = $this->deleteJson(route('api.tags.delete', Str::uuid()));

        $response->assertNotFound();

        $this->assertNotSoftDeleted($tag);
    }

    public function test_forbidden(): void
    {
        $tag = Tag::factory()->create();

        $this->auth();

        $response = $this->deleteJson(route('api.tags.delete', $tag));

        $response->assertForbidden();
    }

    public function test_success(): void
    {
        $tag = Tag::factory()->create();

        $this->auth($tag->user);

        $response = $this->deleteJson(route('api.tags.delete', $tag));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Tag was deleted',
            ]);

        $this->assertSoftDeleted($tag);
    }
}
