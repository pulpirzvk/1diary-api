<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LogoutRequest;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tests\Feature\Auth\LogoutControllerTest;

/**
 * @see LogoutControllerTest
 */
class LogoutController extends Controller
{
    /**
     * Логаут
     *
     * @group Регистрация и аутентификация
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "The access token was deleted"}
     */
    public function __invoke(LogoutRequest $request): JsonResponse
    {
        $request->user()?->currentAccessToken()->delete();

        Auth::guard('web')->logout();

        return Response::success('The access token was deleted');
    }
}
