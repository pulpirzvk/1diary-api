<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
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
