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
