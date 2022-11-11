<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PublishedAtRequest;
use App\Models\Post;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Posts\PublishedAtControllerTest;

/**
 * @see PublishedAtControllerTest
 */
class PublishedAtController extends Controller
{
    /**
     * Изменить дату отображения записи
     *
     * @group Управление записями
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Post was moved"}
     */
    public function __invoke(PublishedAtRequest $request, Post $post): JsonResponse
    {
        $post->update([
            'published_at' => $request->input('to'),
        ]);

        return Response::success('Post was moved');
    }
}
