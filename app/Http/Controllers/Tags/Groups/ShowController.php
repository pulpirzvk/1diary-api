<?php

namespace App\Http\Controllers\Tags\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\Groups\ShowRequest;
use App\Http\Resources\Tags\GroupResource;
use App\Models\Tags\Group;
use Tests\Feature\Tags\Groups\ShowControllerTest;


/**
 * @see ShowControllerTest
 */
class ShowController extends Controller
{
    /**
     * Получить информацию о группе тегов
     *
     * @group Управление тегами
     * @apiResource App\Http\Resources\Tags\GroupResource
     * @apiResourceModel App\Models\Tags\Group
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     */
    public function __invoke(ShowRequest $request, Group $group): GroupResource
    {
        return GroupResource::make($group);
    }
}
