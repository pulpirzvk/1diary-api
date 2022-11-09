<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\ShowRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Tests\Feature\Posts\ShowControllerTest;

/**
 * @see ShowControllerTest
 */
class ShowController extends Controller
{
    /**
     * Получить информацию о записи
     *
     * @group Posts
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     */
    public function __invoke(ShowRequest $request, Post $post): PostResource
    {
        return PostResource::make($post);
    }
}
