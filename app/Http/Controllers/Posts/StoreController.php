<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StoreRequest;
use App\Http\Resources\PostResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Posts\StoreControllerTest;

/**
 * @see StoreControllerTest
 */
class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validated();

        if ($data['published_at'] === null) {
            unset($data['published_at']);
        }

        $post = $user->posts()->create($data)->refresh();

        return PostResource::make($post)
            ->response()
            ->setStatusCode(201);
    }
}
