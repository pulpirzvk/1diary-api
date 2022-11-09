<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\UpdateRequest;
use App\Models\Tag;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Tags\UpdateControllerTest;

/**
 * @see UpdateControllerTest
 */
class UpdateController extends Controller
{
    /**
     * Обновить тег
     *
     * @group Управление тегами
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Tag was updated"}
     */
    public function __invoke(UpdateRequest $request, Tag $tag): JsonResponse
    {
        $tag->update($request->validated());

        return Response::success('Tag was updated');
    }
}
