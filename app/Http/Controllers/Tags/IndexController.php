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
    /**
     * Получить список тегов текущего пользователя
     *
     * @group Управление тегами
     * @apiResourceCollection App\Http\Resources\TagCollection
     * @apiResourceModel App\Models\Tag
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     */
    public function __invoke(IndexRequest $request): TagCollection
    {
        /** @var User $user */
        $user = $request->user();

        $tags = $user->tags()->get();

        return TagCollection::make($tags);
    }
}
