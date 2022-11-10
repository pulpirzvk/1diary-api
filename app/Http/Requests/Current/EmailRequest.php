<?php

namespace App\Http\Requests\Current;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore(auth()->id()),
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'Адрес электронной почты пользователя',
            ],
        ];
    }
}
