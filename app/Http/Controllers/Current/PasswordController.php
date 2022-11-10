<?php

namespace App\Http\Controllers\Current;

use App\Http\Controllers\Controller;
use App\Http\Requests\Current\PasswordRequest;
use App\Models\User;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Current\PasswordControllerTest;

/**
 * @see PasswordControllerTest
 */
class PasswordController extends Controller
{
    /**
     * Обновить пароль
     *
     * @group Текущий пользователь
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Password was updated"}
     */
    public function __invoke(PasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->update([
            'password' => bcrypt($request->input('password')),
        ]);

        return Response::success('Password was updated');
    }
}
