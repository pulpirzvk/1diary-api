<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function auth(User $user = null): ?User
    {
        if ($user === null) {
            $user = User::factory()->create();
        }

        Sanctum::actingAs(
            $user,
            ['*'],
        );

        return $user;
    }
}
