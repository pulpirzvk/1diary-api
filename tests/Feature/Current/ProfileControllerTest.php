<?php

namespace Tests\Feature\Current;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @see ProfileControllerTest
 * @group Current
 */
class ProfileControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthorized(): void
    {
        $response = $this->getJson(route('api.current.profile'));

        $response->assertUnauthorized();
    }

    public function test_success(): void
    {
        $user = $this->auth();

        $response = $this->getJson(route('api.current.profile'));

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
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'email' => $user->email,
                    'is_email_verified' => $user->email_verified_at !== null,
                ],
            ]);
    }
}
