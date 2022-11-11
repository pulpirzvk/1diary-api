<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Auth\RegisterControllerTest;

/**
 * @see RegisterControllerTest
 */
class RegisterController extends Controller
{
    /**
     * Регистрация
     *
     * @unauthenticated
     * @group Регистрация и аутентификация
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Post was updated"}
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        $attributes['password'] = Hash::make($request->input('password'));
        $attributes['type'] = UserType::USER->value;

        $user = User::create($attributes);

        return response()->json([
            'token' => $user->createToken('spa', ['user'])->plainTextToken,
            'token_type' => 'Bearer',
            'id' => $user->id,
        ], 201);
    }
}
