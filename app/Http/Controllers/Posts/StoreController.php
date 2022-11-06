<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\User;

class StoreController extends Controller
{
    public function __invoke(\App\Http\Requests\Posts\StoreRequest $request): PostResource
    {
        /** @var User $user */
        $user = $request->user();

        $post = $user->posts()->create($request->safe())->refresh();

        return PostResource::make($post);
    }
}
