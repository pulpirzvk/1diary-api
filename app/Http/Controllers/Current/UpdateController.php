<?php

namespace App\Http\Controllers\Current;

use App\Http\Controllers\Controller;
use App\Http\Requests\Current\UpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Tests\Feature\Current\UpdateControllerTest;

/**
 * @see UpdateControllerTest
 */
class UpdateController extends Controller
{
    /**
     * Обновить профиль
     *
     * @group Текущий пользователь
     * @apiResource App\Http\Resources\ProfileResource
     * @apiResourceModel App\Models\User
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     */
    public function __invoke(UpdateRequest $request): ProfileResource
    {
        /** @var User $user */
        $user = $request->user();

        $user->update($request->validated());

        return ProfileResource::make($user);
    }
}
