<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\UpdateRequest;
use App\Models\Tag;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Tags\UpdateControllerTest;

/**
 * @see UpdateControllerTest
 */
class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Tag $tag): JsonResponse
    {
        $tag->update($request->validated());

        return Response::success('Tag was updated');
    }
}
