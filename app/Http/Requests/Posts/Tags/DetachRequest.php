<?php

namespace App\Http\Requests\Posts\Tags;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class DetachRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        /** @var Post $post */
        $post = $this->route()->parameter('post');

        /** @var Tag $tag */
        $tag = $this->route()->parameter('tag');

        return $post->user_id === $user->id && $tag->user_id === $user->id;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    public function bodyParameters(): array
    {
        return [
            //
        ];
    }
}
