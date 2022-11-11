<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @see LoginController
 * @group Auth
 */
class LoginControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_forbidden(): void
    {
        $this->markTestSkipped();

        // $this->auth();

        //$response->assertForbidden();
    }

    public function test_success(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'login' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'token',
                'token_type',
            ])
            ->assertJsonFragment([
                'token_type' => 'Bearer',
            ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
            'name' => 'spa',
        ]);
    }

    public function test_login_required(): void
    {
        User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'login' => null,
            'password' => 'password',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('login');
    }

    public function test_login_not_found(): void
    {
        User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'login' => 'xxx@xx.ru',
            'password' => 'password',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('email');
    }

    public function test_login_string(): void
    {
        User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'login' => 12345678,
            'password' => 'password',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('login');
    }

    public function test_password_required(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'login' => $user->email,
            'password' => null,
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('password');
    }

    public function test_password_mistake(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'login' => $user->email,
            'password' => '1',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('email');
    }

    public function test_password_string(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'login' => $user->email,
            'password' => 1,
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('password');
    }
}
