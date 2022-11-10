<?php

namespace Tests\Feature\Current;

use App\Http\Controllers\Current\PasswordController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @see PasswordController
 * @group Current
 */
class PasswordControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->patchJson(route('api.current.password'), []);

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $password = fake()->bothify('####????');

        $response = $this->patchJson(route('api.current.password'), [
            'password' => $password,
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertExactJson([
                'status' => true,
                'message' => 'Password was updated',
            ]);

        $this->assertTrue(Hash::check($password, $user->password));
    }

    public function test_password_required(): void
    {
        $this->auth();

        $response = $this->patchJson(route('api.current.password'), [
            'password' => null,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('password');
    }

    public function test_password_min(): void
    {
        $this->auth();

        $response = $this->patchJson(route('api.current.password'), [
            'password' => '1234567',
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('password');
    }

    public function test_password_letters(): void
    {
        $this->auth();

        $response = $this->patchJson(route('api.current.password'), [
            'password' => '123456789',
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('password');
    }

    public function test_password_numbers(): void
    {
        $this->auth();

        $response = $this->patchJson(route('api.current.password'), [
            'password' => 'notification',
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('password');
    }
}
