<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\ShowRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class ShowController extends Controller
{
    public function __invoke(ShowRequest $request, Post $post): PostResource
    {
        return PostResource::make($post);
    }
}
