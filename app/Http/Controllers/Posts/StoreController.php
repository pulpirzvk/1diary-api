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
    /**
     * Создать запись
     *
     * @group Управление записями
     * @apiResource 201 App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validated();

        if ($data['published_at'] === null) {
            $data['published_at'] = now();
        }

        $post = $user->posts()->create($data)->refresh();

        return PostResource::make($post)
            ->response()
            ->setStatusCode(201);
    }
}
