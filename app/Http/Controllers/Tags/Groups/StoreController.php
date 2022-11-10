<?php

namespace App\Http\Controllers\Tags\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\Groups\StoreRequest;
use App\Http\Resources\Tags\GroupResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Tags\Groups\StoreControllerTest;

/**
 * @see StoreControllerTest
 */
class StoreController extends Controller
{
    /**
     * Создать группу тегов
     *
     * @group Управление тегами
     * @apiResource 201 App\Http\Resources\Tags\GroupResource
     * @apiResourceModel App\Models\Tags\Group
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $group = $user->tagGroups()->create($request->validated());

        return GroupResource::make($group)
            ->response()
            ->setStatusCode(201);
    }
}
