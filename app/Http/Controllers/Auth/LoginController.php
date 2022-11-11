<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\Helper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\Feature\Auth\LoginControllerTest;


/**
 * @see LoginControllerTest
 */
class LoginController extends Controller
{
    /**
     * Логин
     *
     * В качестве логина может выступать номер телефона (в любом формате)
     * или адрес электронной почты
     *
     * @unauthenticated
     * @group Регистрация и аутентификация
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @response status=200 scenario="Success" {"token": "...", "token_type": "Bearer"}
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $login = $request->input('login');

        $password = $request->input('password');

        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        if (!$isEmail) {
            $login = Helper::cleanPhone($login);
        }

        $field = $isEmail ? 'email' : 'phone';

        /** @var User $user */
        $user = User::query()
            ->where($field, '=', $login)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                $field => ['The provided credentials are incorrect.'],
            ]);
        }

        $personalAccessToken = $user->createToken('spa', ['user']);

        return response()->json([
            'token' => $personalAccessToken->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
}
