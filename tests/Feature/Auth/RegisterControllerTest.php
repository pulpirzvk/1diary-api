<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see RegisterController
 * @group Auth
 */
class RegisterControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_success(): void
    {
        $data = [
            'name' => fake()->firstName,
            'email' => fake()->email,
            'password' => 'password1',
        ];

        $response = $this->postJson(route('auth.register'), $data);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'token',
                'token_type',
                'id',
            ])
            ->assertJsonFragment([
                'token_type' => 'Bearer',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);
    }

    public function test_name_required(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'email' => fake()->email,
            'password' => 'password1',
            'name' => null,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_max(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'email' => fake()->email,
            'password' => 'password1',
            'name' => Str::repeat('q', 21),
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_surname(): void
    {
        $email = fake()->email;

        $response = $this->postJson(route('auth.register'), [
            'email' => $email,
            'password' => 'password1',
            'name' => 'Ann Test',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'token',
                'token_type',
                'id',
            ])
            ->assertJsonFragment([
                'token_type' => 'Bearer',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'name' => 'Ann',
            'surname' => 'Test',
        ]);
    }

    public function test_surname_max(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'email' => fake()->email,
            'password' => 'password1',
            'name' => 'Ann ' . Str::repeat('s', 21),
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('surname');
    }

    public function test_email_required(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'email' => null,
            'password' => 'password1',
            'name' => fake()->firstName,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('email');
    }

    public function test_email_email(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'email' => 'this_is_not_email',
            'password' => 'password1',
            'name' => fake()->firstName,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('email');
    }

    public function test_email_unique(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('auth.register'), [
            'email' => $user->email,
            'password' => 'password1',
            'name' => fake()->firstName,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('email');
    }

    public function test_password_required(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'email' => fake()->email,
            'password' => null,
            'name' => fake()->firstName,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('password');
    }

    public function test_password_min(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'email' => fake()->email,
            'password' => '1234567',
            'name' => fake()->firstName,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('password');
    }

    public function test_password_letters(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'email' => fake()->email,
            'password' => '123456789',
            'name' => fake()->firstName,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('password');
    }

    public function test_password_numbers(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'email' => fake()->email,
            'password' => 'password',
            'name' => fake()->firstName,
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('password');
    }
}
