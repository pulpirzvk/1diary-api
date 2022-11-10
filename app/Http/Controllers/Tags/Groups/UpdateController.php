<?php

namespace App\Http\Controllers\Tags\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\Groups\UpdateRequest;
use App\Models\Tags\Group;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Tags\Groups\UpdateControllerTest;

/**
 * @see UpdateControllerTest
 */
class UpdateController extends Controller
{
    /**
     * Обновить группу тегов
     *
     * @group Управление тегами
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Tag group was updated"}
     */
    public function __invoke(UpdateRequest $request, Group $group): JsonResponse
    {
        $group->update($request->validated());

        return Response::success('Tag group was updated');
    }
}
