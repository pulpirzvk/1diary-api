<?php

namespace App\Http\Controllers\Tags\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\Groups\IndexRequest;
use App\Http\Resources\Tags\GroupCollection;
use App\Models\User;
use Tests\Feature\Tags\Groups\IndexControllerTest;

/**
 * @see IndexControllerTest
 */
class IndexController extends Controller
{
    /**
     * Получить список групп тегов
     *
     * @group Управление тегами
     * @apiResourceCollection App\Http\Resources\Tags\GroupCollection
     * @apiResourceModel App\Models\Tags\Group
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     */
    public function __invoke(IndexRequest $request): GroupCollection
    {
        /** @var User $user */
        $user = $request->user();

        $groups = $user->tagGroups()->oldest('name')->get();

        return GroupCollection::make($groups);
    }
}
