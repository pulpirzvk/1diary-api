<?php

namespace App\Http\Controllers\V1\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Posts\ShowRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class ShowController extends Controller
{
    public function __invoke(ShowRequest $request, Post $post): PostResource
    {
        return PostResource::make($post);
    }
}
