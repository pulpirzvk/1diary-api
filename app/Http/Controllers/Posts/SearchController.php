<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Models\User;
use App\Services\PostSearcher\PostSearcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Поиск и фильтрация по записям
     *
     * Если нет ни одного параметра поиска и фильтрации, то возвращаются 5 последних записей
     *
     * @group Управление записями
     * @apiResourceCollection App\Http\Resources\PostCollection
     * @apiResourceModel App\Models\Post
     * @responseFile status=400 scenario="Unauthenticated" responses/defaults/400.json
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $postSearcher = PostSearcher::make($user)
            ->fillFromRequest($request);

        if ($postSearcher->hasEmptyConditions()) {
            $postSearcher->limit(5)
                ->setSortBy('uuid')
                ->setSortDirection('DESC');
        }

        $posts = $postSearcher->get();

        return PostCollection::make($posts);
    }
}
