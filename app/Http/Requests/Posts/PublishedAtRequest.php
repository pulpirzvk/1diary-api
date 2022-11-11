<?php

namespace App\Http\Requests\Posts;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class PublishedAtRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        /** @var Post $post */
        $post = $this->route()->parameter('post');

        return $post->user_id === $user->id;
    }

    public function rules(): array
    {
        return [
            'to' => 'required|date_format:Y-m-d H:i'
        ];
    }

    public function bodyParameters(): array
    {
        return [
            //
        ];
    }
}
