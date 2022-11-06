<?php

namespace App\Http\Controllers\V1\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Posts\StoreRequest;
use App\Http\Resources\PostResource;
use App\Models\User;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): PostResource
    {
        /** @var User $user */
        $user = $request->user();

        $post = $user->posts()->create($request->safe())->refresh();

        return PostResource::make($post);
    }
}
