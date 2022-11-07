<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\DeleteRequest;
use App\Models\Tag;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Tests\Feature\Tags\DeleteControllerTest;

/**
 * @see DeleteControllerTest
 */
class DeleteController extends Controller
{
    public function __invoke(DeleteRequest $request, Tag $tag): JsonResponse
    {
        $tag->delete();

        return Response::success('Tag was deleted');
    }
}
