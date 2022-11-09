<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\DeleteRequest;
use App\Models\Tag;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Tags\DeleteControllerTest;

/**
 * @see DeleteControllerTest
 */
class DeleteController extends Controller
{
    /**
     * Удалить тег
     *
     * @group Управление тегами
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Tag was deleted"}
     */
    public function __invoke(DeleteRequest $request, Tag $tag): JsonResponse
    {
        $tag->delete();

        return Response::success('Tag was deleted');
    }
}
