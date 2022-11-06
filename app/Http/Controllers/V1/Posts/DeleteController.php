<?php

namespace App\Http\Controllers\V1\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Posts\DeleteRequest;
use App\Models\Post;
use App\Services\Response;
use Illuminate\Http\JsonResponse;

class DeleteController extends Controller
{
    public function __invoke(DeleteRequest $request, Post $post): JsonResponse
    {
        $post->delete();

        return Response::success('Post was deleted');
    }
}
