<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\StoreRequest;
use App\Http\Resources\TagResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Tags\StoreControllerTest;

/**
 * @see StoreControllerTest
 */
class StoreController extends Controller
{
    /**
     * Создать тег
     *
     * @group Управление тегами
     * @apiResource 201 App\Http\Resources\TagResource
     * @apiResourceModel App\Models\Tag
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $tag = $user->tags()->create($request->validated())->refresh();

        return TagResource::make($tag)
            ->response()
            ->setStatusCode(201);
    }
}
