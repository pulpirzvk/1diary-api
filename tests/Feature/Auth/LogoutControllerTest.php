<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @see LogoutController
 * @group Auth
 */
class LogoutControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_success(): void
    {
        $this->auth();

        $response = $this->postJson(route('auth.logout'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'The access token was deleted',
            ]);

        $this->assertGuest('web');
    }

    public function test_success_unauthorized(): void
    {
        $response = $this->postJson(route('auth.logout'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'The access token was deleted',
            ]);

        $this->assertGuest('web');
    }
}
