<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\UpdateRequest;
use App\Models\Post;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Posts\UpdateControllerTest;

/**
 * @see UpdateControllerTest
 */
class UpdateController extends Controller
{
    /**
     * Обновить запись
     *
     * @group Posts
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Post was updated"}
     */
    public function __invoke(UpdateRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());

        return Response::success('Post was updated');
    }
}
