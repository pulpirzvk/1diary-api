<?php

namespace App\Http\Controllers\Posts\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\Tags\AttachRequest;
use App\Models\Post;
use App\Models\Tags\Tag;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Posts\Tags\AttachControllerTest;

/**
 * @see AttachControllerTest
 */
class AttachController extends Controller
{
    /**
     * Добавить тег к записи
     *
     * @group Управление записями
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     * @responseFile status=201 scenario="Success" responses/defaults/success.json {"message": "Tag was attached"}
     */
    public function __invoke(AttachRequest $request, Post $post, Tag $tag): JsonResponse
    {
        $post->tags()->syncWithoutDetaching($tag);

        return Response::success('Tag was attached', 201);
    }
}
