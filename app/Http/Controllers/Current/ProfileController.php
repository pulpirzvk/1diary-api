<?php

namespace App\Http\Controllers\Current;

use App\Http\Controllers\Controller;
use App\Http\Requests\Current\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Tests\Feature\Current\ProfileControllerTest;

/**
 * @see ProfileControllerTest
 */
class ProfileController extends Controller
{
    /**
     * Получить профиль текущего пользователя
     *
     * @group Текущий пользователь
     * @apiResource App\Http\Resources\ProfileResource
     * @apiResourceModel App\Models\User
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     */
    public function __invoke(ProfileRequest $request): ProfileResource
    {
        /** @var User $user */
        $user = $request->user();

        return ProfileResource::make($user);
    }
}
