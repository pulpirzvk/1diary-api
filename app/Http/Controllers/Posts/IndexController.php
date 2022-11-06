<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\IndexRequest;
use App\Http\Resources\PostCollection;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request): PostCollection
    {
        /** @var User $user */
        $user = $request->user();

        $posts = $user->posts()->oldest('published_at')->get();

        return PostCollection::make($posts);
    }
}
