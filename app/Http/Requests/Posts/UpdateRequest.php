<?php

namespace App\Http\Requests\Posts;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title' => 'nullable|string|max:80',
            'content' => 'required|string|max:9000',
            'published_at' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Заголовок',
            ],
            'content' => [
                'description' => 'Контент',
            ],
            'published_at' => [
                'description' => 'К какому времени относится запись',
            ],
        ];
    }
}
