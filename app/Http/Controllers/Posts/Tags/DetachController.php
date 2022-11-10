<?php

namespace App\Http\Controllers\Posts\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\Tags\DetachRequest;
use App\Models\Post;
use App\Models\Tags\Tag;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Posts\Tags\DetachControllerTest;

/**
 * @see DetachControllerTest
 */
class DetachController extends Controller
{
    /**
     * Удалить тег у записи
     *
     * @group Управление записями
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Tag was detached"}
     */
    public function __invoke(DetachRequest $request, Post $post, Tag $tag): JsonResponse
    {
        $post->tags()->detach($tag);

        return Response::success('Tag was detached');
    }
}
