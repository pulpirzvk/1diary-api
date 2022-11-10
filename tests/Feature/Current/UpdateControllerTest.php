<?php

namespace Tests\Feature\Current;

use App\Http\Controllers\Current\UpdateController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @see UpdateController
 * @group Current
 */
class UpdateControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->putJson(route('api.current.profile.update'), []);

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $name = Str::random();

        $surname = Str::random();

        $response = $this->putJson(route('api.current.profile.update'), [
            'name' => $name,
            'surname' => $surname,
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'surname',
                    'email',
                    'is_email_verified',
                ],
            ])
            ->assertExactJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $user->email,
                    'is_email_verified' => $user->email_verified_at !== null,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $name,
            'surname' => $surname,
        ]);
    }

    public function test_name_required(): void
    {
        $this->auth();

        $response = $this->putJson(route('api.current.profile.update'), [
            'name' => null,
            'surname' => Str::random(),
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_name_max(): void
    {
        $this->auth();

        $response = $this->putJson(route('api.current.profile.update'), [
            'name' => Str::repeat('a', 21),
            'surname' => Str::random(),
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('name');
    }

    public function test_surname_max(): void
    {
        $this->auth();

        $response = $this->putJson(route('api.current.profile.update'), [
            'surname' => Str::repeat('a', 21),
            'name' => Str::random(),
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('surname');
    }

    public function test_surname_optional(): void
    {
        $user = $this->auth();

        $name = Str::random();

        $response = $this->putJson(route('api.current.profile.update'), [
            'name' => $name,
            'surname' => null,
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'surname',
                    'email',
                    'is_email_verified',
                ],
            ])
            ->assertExactJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $name,
                    'surname' => null,
                    'email' => $user->email,
                    'is_email_verified' => $user->email_verified_at !== null,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $name,
            'surname' => null,
        ]);
    }
}
