<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\IndexRequest;
use App\Http\Resources\PostCollection;
use App\Models\User;
use Tests\Feature\Posts\IndexControllerTest;

/**
 * @see IndexControllerTest
 */
class IndexController extends Controller
{
    /**
     * Получить список записей текущего пользователя
     *
     * @group Posts
     * @apiResourceCollection App\Http\Resources\PostCollection
     * @apiResourceModel App\Models\Post
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     */
    public function __invoke(IndexRequest $request): PostCollection
    {
        /** @var User $user */
        $user = $request->user();

        $posts = $user->posts()->oldest('published_at')->get();

        return PostCollection::make($posts);
    }
}
