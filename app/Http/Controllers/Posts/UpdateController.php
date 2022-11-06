<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\UpdateRequest;
use App\Models\Post;
use App\Services\Response;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());

        return Response::success('Post was updated');
    }
}
