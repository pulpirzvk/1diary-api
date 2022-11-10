<?php

namespace App\Http\Requests\Tags\Groups;

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
            'name' => 'required|string|max:40',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Имя группы тегов',
            ],
        ];
    }
}
