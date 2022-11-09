<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\DeleteRequest;
use App\Models\Post;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Posts\DeleteControllerTest;

/**
 * @see DeleteControllerTest
 */
class DeleteController extends Controller
{
    /**
     * Удалить запись
     *
     * @group Управление записями
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Post was deleted"}
     */
    public function __invoke(DeleteRequest $request, Post $post): JsonResponse
    {
        $post->delete();

        return Response::success('Post was deleted');
    }
}
