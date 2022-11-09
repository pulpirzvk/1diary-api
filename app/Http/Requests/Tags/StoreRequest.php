<?php

namespace App\Http\Requests\Tags;

use App\Rules\TagSlug;
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
            'name' => [
                'bail',
                'required',
                'string',
                'max:20',
                new TagSlug,
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Имя тега. Должно быть уникально без учета регистра.',
            ],
        ];
    }
}
