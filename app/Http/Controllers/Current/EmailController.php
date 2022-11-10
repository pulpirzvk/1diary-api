<?php

namespace App\Http\Controllers\Current;

use App\Http\Controllers\Controller;
use App\Http\Requests\Current\EmailRequest;
use App\Models\User;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Current\EmailControllerTest;

/**
 * @see EmailControllerTest
 */
class EmailController extends Controller
{
    /**
     * Обновить e-mail
     *
     * @group Текущий пользователь
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Email was updated"}
     * @responseFile status=200 scenario="Same email" responses/defaults/empty.json
     */
    public function __invoke(EmailRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $email = $request->input('email');

        if ($email !== $user->email) {
            $user->update([
                'email' => $email,
                'email_verified_at' => null,
            ]);

            return Response::success('Email was updated');
        }

        return Response::success();
    }
}
