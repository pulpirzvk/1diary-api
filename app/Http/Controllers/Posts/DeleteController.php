<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\Response;
use Illuminate\Http\JsonResponse;

class DeleteController extends Controller
{
    public function __invoke(\App\Http\Requests\Posts\DeleteRequest $request, Post $post): JsonResponse
    {
        $post->delete();

        return Response::success('Post was deleted');
    }
}
