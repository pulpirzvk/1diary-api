<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\IndexRequest;
use App\Http\Resources\TagCollection;
use App\Models\User;
use Tests\Feature\Tags\IndexControllerTest;

/**
 * @see IndexControllerTest
 */
class IndexController extends Controller
{
    public function __invoke(IndexRequest $request): TagCollection
    {
        /** @var User $user */
        $user = $request->user();

        $tags = $user->tags()->get();

        return TagCollection::make($tags);
    }
}
