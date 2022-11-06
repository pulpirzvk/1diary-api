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
    public function __invoke(DeleteRequest $request, Post $post): JsonResponse
    {
        $post->delete();

        return Response::success('Post was deleted');
    }
}
