<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
//use Tests\Feature\Auth\SerachControllerTest;

/**
 * @see SearchControllerTest
 */
class SearchController extends Controller
{
    /**
     *
     *
     * @group
     * @apiResource App\Http\Resources\
     * @apiResourceModel App\Models\
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     * @responseFile status=422 scenario="Unprocessable" responses/defaults/422.json
     * @responseFile status=403 scenario="Forbidden" responses/defaults/403.json
     * @responseFile status=404 scenario="Not found" responses/defaults/404.json
     * @responseFile status=200 scenario="Success" responses/defaults/success.json {"message": "Post was updated"}
     */
    public function __invoke(Request $request): JsonResponse
    {
        return Response::success('');
    }
}
