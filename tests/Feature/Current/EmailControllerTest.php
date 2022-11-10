<?php

namespace Tests\Feature\Current;

use App\Http\Controllers\Current\EmailController;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see EmailController
 * @group Current
 */
class EmailControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->patchJson(route('api.current.email'), []);

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $email = Str::random() . '@example.com';

        $response = $this->patchJson(route('api.current.email'), [
            'email' => $email,
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Email was updated',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $email,
            'email_verified_at' => null,
        ]);
    }

    public function test_email_required(): void
    {
        $this->auth();

        $response = $this->patchJson(route('api.current.email'), [
            'email' => null,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('email');
    }

    public function test_email_email(): void
    {
        $this->auth();

        $response = $this->patchJson(route('api.current.email'), [
            'email' => Str::random(),
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('email');
    }

    public function test_email_max(): void
    {
        $this->auth();

        $response = $this->patchJson(route('api.current.email'), [
            'email' => Str::repeat('a', 255) . '@example.com',
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('email');
    }

    public function test_email_unique(): void
    {
        $this->auth();

        $user = User::factory()->create();

        $response = $this->patchJson(route('api.current.email'), [
            'email' => $user->email,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('email');
    }
}
