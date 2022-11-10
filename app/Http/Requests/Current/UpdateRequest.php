<?php

namespace App\Http\Requests\Current;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:20',
            'surname' => 'nullable|string|max:20',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Имя',
            ],
            'surname' => [
                'description' => 'Фамилия',
            ],
        ];
    }
}
